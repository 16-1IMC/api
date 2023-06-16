<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\SocialNetwork;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Create 20 users
        $users = array(); 
        for ($i = 0; $i < 20; $i++) {
            $users[$i] = new User();
            $users[$i]->setEmail($faker->email());
            $users[$i]->setPassword($faker->password());
            $users[$i]->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
            $users[$i]->setIsAdmin(false);
            $manager->persist($users[$i]);
        }
        
        // Create admin user
        $users[20] = new User();
        $users[20]->setEmail("admin@stylestock.com");
        $users[20]->setPassword("adminSS1234");
        $users[20]->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
        $users[20]->setIsAdmin(true);
        $manager->persist($users[20]);

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
        $socialNetworksNameList = ["Facebook", "Instagram", "Twitter", "Pinterest", "TikTok"];
        for ($i = 0; $i < 5; $i++) {
            $brands[$i] = new Brand();
            $brands[$i]->setName($faker->company());
            $brands[$i]->setEmail($faker->email());
            $brands[$i]->setPassword($faker->password());
            $brands[$i]->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
            $brands[$i]->addCategory($categories[array_rand($categories)]);
            $socialNetworks = array();
            for ($j = 0; $j < rand(1, 4); $j++) {
                $socialNetworks[$j] = new SocialNetwork();
                $socialNetworks[$j]->setName($socialNetworksNameList[$j]);
                $socialNetworks[$j]->setLink($faker->url());
                $socialNetworks[$j]->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
                $socialNetworks[$j]->setBrandId($brands[$i]);
                $manager->persist($socialNetworks[$j]);
            }
            $manager->persist($brands[$i]);
        }

        // Create 50 posts
        $posts = array();
        for ($i = 0; $i < 50; $i++) {
            $posts[$i] = new Post();
            $posts[$i]->setTitle($faker->sentence($nbWords = rand(4, 10), $variableNbWords = true));
            $posts[$i]->setContent($faker->text($maxNbChars = 255));
            $posts[$i]->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime($max = 'now')));
            $posts[$i]->setAuthor($brands[array_rand($brands)]);
            $manager->persist($posts[$i]);
        }

        $manager->flush();

    }
}