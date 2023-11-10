<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\CharacterRepository;
use App\Repository\FightRepository;
use App\Repository\VotesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RandomFightController extends AbstractController
{
    #[Route('/randomfight', name: 'app_random_fight')]
    public function index(FightRepository $fightRepository, CategoryRepository $categoryRepository): Response
    {
        $allFights = $fightRepository->findBy(['isBalanced' => true]);

        $specificCategory = $categoryRepository->findBy(['code' => 'MAN']);

        $randomKey = array_rand($allFights, 1); // Get a random key in allFights
        $randomFight = $allFights[$randomKey]; // Get a random Fight by a randomKey.

        return $this->render('random_fight/index.html.twig', [
            'randomFight' => $randomFight,
            'controller_name' => 'RandomFight',
        ]);
    }

    #[Route('/addVote/{vote_id}', name: 'app_add_vote', methods: ['GET'])]
    public function addVote(VotesRepository $votesRepository, int $vote_id, EntityManagerInterface $entityManager): Response
    {
        $vote = $votesRepository->findOneBy(['id' => $vote_id]);
        $vote->setNumberOfVote($vote->getNumberOfVote() + 1);
        $entityManager->flush();

        return $this->redirectToRoute('app_random_fight');
    }
}
