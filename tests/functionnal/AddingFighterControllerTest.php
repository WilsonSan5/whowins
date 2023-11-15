<?php

namespace App\Tests\Controller;

use App\Entity\Fight;
use App\Entity\Fighter;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// The database resets before each test thanks to DAMADoctrineTestBundle
class AddingFighterControllerTest extends WebTestCase
{
    public function testAddingFighter()
    {
        // Load the registration page
        $client = static::createClient();
        $crawler = $client->request("GET", "/fighter/new");
        $doctrine = $client->getContainer()->get('doctrine');

        // Getting the form
        $form = $crawler->selectButton('Save')->form([
            'fighter[name]' => 'TestFighter',
            'fighter[strength]' => '5',
            'fighter[is_valid]' => true,
            'fighter[category]' => 1,
        ]);
        // Submit the form
        $client->submit($form);

        // -------- Checking if TestFighter has been created in database -----------
        // Importing Fighter Repository
        $fighterRepository = $doctrine->getRepository(Fighter::class);

        // Finding the TestFighter in repository
        $testFighter = $fighterRepository->findOneBy(['name' => 'TestFighter']);
        // Checking if he exists
        $this->assertEquals('TestFighter', $testFighter->getName());

        // ------- Checking if all fights has been created in database -------------
        // The number of fights should be equal to the number of fighter in database - 1 because we don't count the fighter itself
        $this->assertEquals(count($testFighter->getFights()), count($fighterRepository->findAll()) - 1);
    }

    public function testFight_isBalancedValue()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');

        $fightRepository = $doctrine->getRepository(Fight::class);
        $allFights = $fightRepository->findAll();

        // Checking if when the strenght difference is less than 2, the isBalanced value is set to true
        foreach ($allFights as $fight) {
            if (abs($fight->getFighters()[0]->getStrength() - $fight->getFighters()[1]->getStrength()) < 2) {
                $this->assertTrue($fight->isIsBalanced());
            } else {
                $this->assertFalse($fight->isIsBalanced());
            }
        }
    }
}