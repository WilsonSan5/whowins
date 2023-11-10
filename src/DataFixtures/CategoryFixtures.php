<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Categories
        $categories = [
            ['title' => 'Comics', 'code' => 'COM'],
            ['title' => 'Movie / TV Show', 'code' => 'MOV'],
            ['title' => 'Manga / Anime', 'code' => 'MAN'],
            ['title' => 'Video Games', 'code' => 'GAM'],
            ['title' => 'Mythology', 'code' => 'MYT'],
        ];
        foreach ($categories as $category) {
            $newCategory = new Category;
            $newCategory->setTitle($category['title']);
            $newCategory->setCode($category['code']);
            $this->addReference($category['code'], $newCategory); // Adding reference to share categories with CharacterFixtures.php
            $manager->persist($newCategory);
        }

        // Characters

        $manager->flush();
    }
}
