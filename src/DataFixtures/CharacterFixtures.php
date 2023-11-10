<?php

namespace App\DataFixtures;


use App\Entity\Category;
use App\Entity\Character;
use App\Entity\Fight;
use App\Entity\Votes;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class CharacterFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Default characters'data
        $characters_data = [
            ['name' => 'Darth Vader', 'strength' => 4, 'category_code' => 'MOV'],
            ['name' => 'Spiderman', 'strength' => 4, 'category_code' => 'COM'],
            ['name' => 'Gandalf', 'strength' => 4, 'category_code' => 'MOV'],
            ['name' => 'Kratos', 'strength' => 4, 'category_code' => 'GAM'],
            ['name' => 'Son Goku', 'strength' => 5, 'category_code' => 'MAN'],
            ['name' => 'Naruto', 'strength' => 5, 'category_code' => 'MAN'],
            ['name' => 'Iron Man', 'strength' => 4, 'category_code' => 'COM'],
            ['name' => 'Thanos', 'strength' => 5, 'category_code' => 'COM'],
            ['name' => 'Lara Croft', 'strength' => 3, 'category_code' => 'GAM'],
            ['name' => 'James Bond', 'strength' => 3, 'category_code' => 'MOV'],
            ['name' => 'Black Widow', 'strength' => 3, 'category_code' => 'COM'],
            ['name' => 'Ezio Auditore', 'strength' => 3, 'category_code' => 'GAM'],
            ['name' => 'Luffy', 'strength' => 5, 'category_code' => 'MAN']
        ];
        // Characters'object
        $characters_object = [];

        foreach ($characters_data as $character) {
            $newCharacter = new Character;
            $newCharacter->setName($character['name']);
            $newCharacter->setStrength($character['strength']);
            $newCharacter->setCategory($this->getReference($character['category_code']));
            array_push($characters_object, $newCharacter);

            $manager->persist($newCharacter);
        }

        // Generating fights

        // Each character fights every others characters. No duplication. 
        foreach ($characters_object as $fighter_1) {
            foreach (array_slice($characters_object, 1) as $fighter_2) {
                // Prevent characters fighting themselves
                if ($fighter_1->getName() !== $fighter_2->getName()) {
                    // Creating the fight
                    $newFight = new Fight;
                    $newFight->setIsValid(true);

                    // Setting isBalanced : If the strength gap between 2 characters is less than 2, the fight is balanced.
                    if (abs($fighter_1->getStrength() - $fighter_2->getStrength()) < 2) {
                        $newFight->setIsBalanced(true);
                    } else
                        $newFight->setIsBalanced(false);

                    // Setting the votes for fighter 1
                    $fighter_1_votes = new Votes;
                    $fighter_1_votes->setNumberOfVote(0);
                    $fighter_1_votes->setFighter($fighter_1);
                    $manager->persist($fighter_1_votes);

                    // Setting the votes for fighter 2
                    $fighter_2_votes = new Votes;
                    $fighter_2_votes->setNumberOfVote(0);
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
