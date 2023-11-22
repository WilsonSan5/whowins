<?php

namespace App\DataFixtures;


use App\Entity\Category;

use App\Entity\Fight;
use App\Entity\Fighter;
use App\Entity\Vote;
use App\Entity\Votes;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class FighterFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Default characters'data
        $fighter_data = [
            ['name' => 'Darth Vader', 'strength' => 4, 'category_code' => 'MOV', 'is_valid' => true],
            ['name' => 'Spiderman', 'strength' => 4, 'category_code' => 'COM', 'is_valid' => true],
            ['name' => 'Gandalf', 'strength' => 4, 'category_code' => 'MOV', 'is_valid' => true],
            ['name' => 'Kratos', 'strength' => 4, 'category_code' => 'GAM', 'is_valid' => true],
            ['name' => 'Son Goku', 'strength' => 5, 'category_code' => 'MAN', 'is_valid' => true],
            ['name' => 'Naruto', 'strength' => 5, 'category_code' => 'MAN', 'is_valid' => true],
            ['name' => 'Iron Man', 'strength' => 4, 'category_code' => 'COM', 'is_valid' => true],
            ['name' => 'Thanos', 'strength' => 5, 'category_code' => 'COM', 'is_valid' => true],
            ['name' => 'Lara Croft', 'strength' => 3, 'category_code' => 'GAM', 'is_valid' => true],
            ['name' => 'James Bond', 'strength' => 3, 'category_code' => 'MOV', 'is_valid' => true],
            ['name' => 'Black Widow', 'strength' => 3, 'category_code' => 'COM', 'is_valid' => true],
            ['name' => 'Ezio Auditore', 'strength' => 3, 'category_code' => 'GAM', 'is_valid' => true],
            ['name' => 'Luffy', 'strength' => 5, 'category_code' => 'MAN', 'is_valid' => true],
            ['name' => 'Harry Potter', 'strength' => 3, 'category_code' => 'MOV', 'is_valid' => true],
            ['name' => 'Saitama', 'strength' => 5, 'category_code' => 'MAN', 'is_valid' => true],
            ['name' => 'Katniss Evergreen', 'strength' => 3, 'category_code' => 'MOV', 'is_valid' => true],
            ['name' => 'Captain Marvel', 'strength' => 5, 'category_code' => 'COM', 'is_valid' => true],
            ['name' => 'Master Chief', 'strength' => 4, 'category_code' => 'GAM', 'is_valid' => true],
            ['name' => 'King Kong', 'strength' => 4, 'category_code' => 'MOV', 'is_valid' => true],
            ['name' => 'Superman', 'strength' => 5, 'category_code' => 'COM', 'is_valid' => true],
            ['name' => 'Wonder Woman', 'strength' => 4, 'category_code' => 'COM', 'is_valid' => true],
            ['name' => 'Astérix', 'strength' => 3, 'category_code' => 'COM', 'is_valid' => true],
            ['name' => 'Ryu', 'strength' => 3, 'category_code' => 'GAM', 'is_valid' => true],
            ['name' => 'Link', 'strength' => 3, 'category_code' => 'GAM', 'is_valid' => true],
            ['name' => 'John Snow', 'strength' => 3, 'category_code' => 'MOV', 'is_valid' => true],
            ['name' => 'Luffy', 'strength' => 5, 'category_code' => 'MAN', 'is_valid' => true],
            ['name' => 'Freezer', 'strength' => 5, 'category_code' => 'MAN', 'is_valid' => true],
            ['name' => 'Geralt of Rivia', 'strength' => 5, 'category_code' => 'GAM', 'is_valid' => true],
            ['name' => 'Godzilla', 'strength' => 5, 'category_code' => 'MOV', 'is_valid' => true],
            ['name' => 'Hulk', 'strength' => 5, 'category_code' => 'COM', 'is_valid' => true],
            ['name' => 'Pikachu', 'strength' => 3, 'category_code' => 'GAM', 'is_valid' => true],
            ['name' => 'Wolverine', 'strength' => 3, 'category_code' => 'MAN', 'is_valid' => true]
            // ['name' => '', 'strength' => , 'category_code' => '', 'is_valid' => true],
        ];
        // Characters'object
        $fighters_object = [];

        foreach ($fighter_data as $fighter) {
            $newFighter = new Fighter;
            $newFighter->setName($fighter['name']);
            $newFighter->setStrength($fighter['strength']);
            $newFighter->setCategory($this->getReference($fighter['category_code']));
            $newFighter->setIsValid(true);
            array_push($fighters_object, $newFighter);

            $manager->persist($newFighter);
        }
        // Each character fights every others characters. No duplication. 
        foreach ($fighters_object as $key1 => $fighter_1) {
            // Prevent characters fighting themselves
            foreach ($fighters_object as $key2 => $fighter_2) {
                if (
                    $fighter_1->getName() !== $fighter_2->getName() &&
                    $key1 < $key2
                ) {
                    // Creating the fight
                    $newFight = new Fight;
                    // Add Fighters to Fight
                    $newFight->addFighter($fighter_1);
                    $newFight->addFighter($fighter_2);

                    // Setting isBalanced : If the strength gap between 2 characters is less than 2, the fight is balanced.
                    if (abs($fighter_1->getStrength() - $fighter_2->getStrength()) < 2) {
                        $newFight->setIsBalanced(true);
                    } else
                        $newFight->setIsBalanced(false);

                    // Setting the votes for fighter 1
                    $fighter_1_votes = new Vote;
                    $fighter_1_votes->setNumberOfVotes(0);
                    $fighter_1_votes->setFighter($fighter_1);
                    $manager->persist($fighter_1_votes);

                    // Setting the votes for fighter 2
                    $fighter_2_votes = new Vote;
                    $fighter_2_votes->setNumberOfVotes(0);
                    $fighter_2_votes->setFighter($fighter_2);
                    $manager->persist($fighter_2_votes);

                    $newFight->addVote($fighter_1_votes);
                    $newFight->addVote($fighter_2_votes);

                    $manager->persist($newFight);
                }
            }
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['characterFixtures'];
    }
}
