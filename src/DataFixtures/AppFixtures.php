<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Like;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Image;
use App\Entity\Follow;
use DateTimeImmutable;
use App\Entity\Category;
use App\Entity\SocialNetwork;
use App\Faker\Provider\CustomImageProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Create 25 users
        $users = array(); 
        for ($i = 0; $i < 25; $i++) {
            $users[$i] = new User();
            $users[$i]->setEmail($faker->email());
            $users[$i]->setPassword('$2y$13$fAeeVb0Phf6bv0qlkPY90uc2AYCpyVr8W6Tu06V6ID/JBXBJ9EZA.'); // MDP = Password
            $users[$i]->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
            $users[$i]->setRoles(['USER']);
            $manager->persist($users[$i]);
        }
        
        // Create admin user
        $users[45] = new User();
        $users[45]->setEmail("admin@stylestock.com");
        $users[45]->setPassword('$2y$13$fAeeVb0Phf6bv0qlkPY90uc2AYCpyVr8W6Tu06V6ID/JBXBJ9EZA.'); // MDP = Password
        $users[45]->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
        $users[45]->setRoles(['ADMIN']);
        $manager->persist($users[45]);

        // Create 5 categories
        $categoryNameList = ["Streetwear", "Sportswear", "Casual", "Formal", "Business"];
        $categories = array();
        for ($i = 0; $i < 5; $i++) {
            $categories[$i] = new Category();
            $categories[$i]->setName($categoryNameList[$i]);
            $manager->persist($categories[$i]);
        }

        // Create 6 brands
        $brands = array();
        $brandStatus = ["PENDING", "APPROVED"];
        $socialNetworksNameList = ["Facebook", "Instagram", "Twitter", "Pinterest", "TikTok", "Website"];
        for ($i = 0; $i < 6; $i++) {
            $brandProfilePicture = new Image();
            $brandProfilePicture->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
            $brandProfilePicture->setPath("stylestock_brand_image_" . $i + 1 . ".png");
            $manager->persist($brandProfilePicture);

            $brandBanner = new Image();
            $brandBanner->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
            $brandBanner->setPath("stylestock_brand_banner_" . $i + 1 . ".png");
            $manager->persist($brandBanner);

            $brands[$i] = new Brand();
            $brands[$i]->setName($faker->company());
            $brands[$i]->setEmail($faker->email());
            $brands[$i]->setProfilePicture($brandProfilePicture);
            $brands[$i]->setBanner($brandBanner);
            $brands[$i]->setPassword('$2y$13$CrASJ2E5ogwy.TMA2xn/ZuCcl2rdIIwfCmU0ajTxwven.BTSzwzTq');
            $brands[$i]->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
            $brands[$i]->addCategory($categories[array_rand($categories)]);
            $brands[$i]->setStatus($faker->randomElement($brandStatus));
            $socialNetworks = array();
            for ($j = 0; $j < rand(1, 5); $j++) {
                $socialNetworks[$j] = new SocialNetwork();
                $socialNetworks[$j]->setName($socialNetworksNameList[array_rand($socialNetworksNameList)]);
                $socialNetworks[$j]->setLink($faker->url());
                $socialNetworks[$j]->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
                $socialNetworks[$j]->setBrandId($brands[$i]);
                $manager->persist($socialNetworks[$j]);
            }
            $manager->persist($brands[$i]);
        }

        // Create 25 Follows
        $follows = array();
        for ($i = 0; count($follows) < 25; $i++) {
            $follow = new Follow();
            $follow->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
            $follow->setUser($users[array_rand($users)]);
            $follow->setBrand($brands[array_rand($brands)]);
            if (!array_search($follow->getUser(), $follows) || !array_search($follow->getBrand(), $follows)) {
                $follows[$i] = $follow;
                $manager->persist($follow);
            }
        }

        // Create 7 images
        $images = array();
        for ($i = 0; $i < 7; $i++) {
            $images[$i] = new Image();
            $images[$i]->setPath("stylestock_post_image_" . $i + 1 . ".png");
            $images[$i]->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
            $manager->persist($images[$i]);
        }

        $posts = array();

        // Create Pumba post
        $pumbaPost = new Post();
        $pumbaPost->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
        $pumbaPost->setAuthor($brands[0]);
        $pumbaPost->setTitle("Nouvelle collection : Pumba et les 40 chasseurs");
        $pumbaPost->setContent("Pumba et les 40 chasseurs est une collection de vêtements pour homme et femme. Elle est composée de 40 pièces uniques, toutes fabriquées à la main. Les vêtements sont fabriqués à partir de tissus recyclés et sont donc éco-responsables.");
        $pumbaPost->addImage($images[0]);
        array_push($posts, $pumbaPost);
        $manager->persist($pumbaPost);

        // Create Abibas post 1
        $abibasPost1 = new Post();
        $abibasPost1->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
        $abibasPost1->setAuthor($brands[1]);
        $abibasPost1->setTitle("Abibas x F-COURD");
        $abibasPost1->setContent("Abibas s'associe à F-COURD pour une collection de vêtements pour devenir un vrai sapeur comme F-COURD.");
        $abibasPost1->addImage($images[1]);
        array_push($posts, $abibasPost1);
        $manager->persist($abibasPost1);

        // Create Abibas post 2
        $abibasPost2 = new Post();
        $abibasPost2->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
        $abibasPost2->setAuthor($brands[1]);
        $abibasPost2->setTitle("Abibas CDM 2010 - Tribute");
        $abibasPost2->setContent("Abibas rend hommage à la Coupe du Monde 2010 en Afrique du Sud avec une collection de vêtements aux couleurs de l'Afrique du Sud co-designé par Florent Malouda.");
        $abibasPost2->addImage($images[6]);
        array_push($posts, $abibasPost2);
        $manager->persist($abibasPost2);

        // Create Sotizerie post 
        $sotizeriePost = new Post();
        $sotizeriePost->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
        $sotizeriePost->setAuthor($brands[2]);
        $sotizeriePost->setTitle("Sotizerie Edition Spéciale Tour de France");
        $sotizeriePost->setContent("Pret à soutenir Alain-Philippe lors de ce TDF 2023 ? Retrouvez notre collection spéciale Tour de France dans nos boutiques et sur notre site internet.");
        $sotizeriePost->addImage($images[2]);
        $sotizeriePost->addImage($images[3]);
        array_push($posts, $sotizeriePost);
        $manager->persist($sotizeriePost);

        // Create Fail post
        $failPost = new Post();
        $failPost->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
        $failPost->setAuthor($brands[3]);
        $failPost->setTitle("Fail : la marque qui ne marche pas");
        $failPost->setContent("Fail est une marque de vêtements qui ne marche pas. Nous avons décidé de créer cette marque pour vous montrer que nous sommes capables de faire des vêtements qui ne marchent pas. Ca marche ?");
        $failPost->addImage($images[4]);
        array_push($posts, $failPost);
        $manager->persist($failPost);

        // Create Lactose post
        $lactosePost = new Post();
        $lactosePost->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
        $lactosePost->setAuthor($brands[4]);
        $lactosePost->setTitle("Lactose x Les Produits Laitiers");
        $lactosePost->setContent("Lactose s'associe aux Produits Laitiers pour une collection de vêtements en cuir de vâche.");
        $lactosePost->addImage($images[5]);
        array_push($posts, $lactosePost);
        $manager->persist($lactosePost);

        // Create 25 likes
        $likes = array();
        for ($i = 0; $i < 25; $i++) {
            $like = new Like();
            $like->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
            $like->setUserId($users[array_rand($users)]);
            $like->setPostId($posts[array_rand($posts)]);
            if (!array_search($like->getUserId(), $likes) || !array_search($like->getPostId(), $likes)) {
                $likes[$i] = $like;
                $manager->persist($like);
            };
        }

        $manager->flush();
    }
}