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
        $faker->addProvider(new CustomImageProvider($faker));

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

        // Create 5 brands
        $brands = array();
        $brandStatus = ["PENDING", "APPROVED"];
        $socialNetworksNameList = ["Facebook", "Instagram", "Twitter", "Pinterest", "TikTok", "Website"];
        for ($i = 0; $i < 5; $i++) {
            $brandImages = array();

            for ($j = 0; $j < 2; $j++) {
                $brandImages[$j] = new Image();
                $brandImages[$j]->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
                $brandImages[$j]->setPath("stylestock_brand_image_" . $i . "_" . $j . ".png");
                $brandImages[$j]->setContentUrl($faker->imageUrl($width = 640, $height = 480));
                $manager->persist($brandImages[$j]);
            }

            $brands[$i] = new Brand();
            $brands[$i]->setName($faker->company());
            $brands[$i]->setEmail($faker->email());
            $brands[$i]->setProfilePicture($brandImages[0]);
            $brands[$i]->setBanner($brandImages[1]);
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

        // Create 20 images
        $images = array();
        for ($i = 0; $i < 20; $i++) {
            $images[$i] = new Image();
            $images[$i]->setPath("stylestock_post_image_" . $i . ".png");
            $images[$i]->setContentUrl($faker->imageUrl(640, 480));
            $images[$i]->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
            $manager->persist($images[$i]);
        }

        // Create 10 posts
        $posts = array();
        for ($i = 0; $i < 10; $i++) {
            $posts[$i] = new Post();
            $posts[$i]->setTitle($faker->sentence($nbWords = rand(4, 10), $variableNbWords = true));
            $posts[$i]->setContent($faker->text($maxNbChars = 255));
            $posts[$i]->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
            for ($j = 0; $j < rand(1, 3); $j++) {
                $imageIndex = array_rand($images);
                $posts[$i]->addImage($images[$imageIndex]); 
            }
            $posts[$i]->setAuthor($brands[array_rand($brands)]);
            $manager->persist($posts[$i]);
        }

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