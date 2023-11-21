<?php

namespace App\Controller;

use App\Entity\Fighter;
use App\Entity\Fight;
use App\Entity\Vote;
use App\Form\FighterType;
use App\Repository\FighterRepository;
use App\Repository\FightRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/fighter')]
class FighterController extends AbstractController
{
    #[Route('/', name: 'app_fighter_index', methods: ['GET'])]
    public function index(FighterRepository $fighterRepository): Response
    {
        return $this->render('fighter/index.html.twig', [
            'fighters' => $fighterRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fighter_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FighterRepository $fighterRepository): Response
    {
        $fighter = new Fighter();
        $form = $this->createForm(FighterType::class, $fighter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Generating new fighter's fights with all other fighter
            $allFighters = $fighterRepository->findAll();
            foreach ($allFighters as $fighter_2) {
                $newFight = new Fight();
                $newFight->addFighter($fighter);
                $newFight->addFighter($fighter_2);

                // Setting isBalanced : If the strength gap between 2 characters is less than 2, the fight is balanced.
                if (abs($fighter->getStrength() - $fighter_2->getStrength()) < 2) {
                    $newFight->setIsBalanced(true);
                } else
                    $newFight->setIsBalanced(false);

                // Setting the votes for the new fighter
                $newFighter_vote = new Vote;
                $newFighter_vote->setNumberOfVotes(0);
                $newFighter_vote->setFighter($fighter);
                $newFight->addVote($newFighter_vote);

                // Setting the votes for fighter 2
                $fighter_2_votes = new Vote;
                $fighter_2_votes->setNumberOfVotes(0);
                $fighter_2_votes->setFighter($fighter_2);
                $newFight->addVote($fighter_2_votes);

                $entityManager->persist($newFighter_vote);
                $entityManager->persist($fighter_2_votes);
                $entityManager->persist($newFight);
            }
            $entityManager->persist($fighter);
            $entityManager->flush();

            return $this->redirectToRoute('app_fighter_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('fighter/new.html.twig', [
            'fighter' => $fighter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fighter_show', methods: ['GET'])]
    public function show(Fighter $fighter, FightRepository $fightRepository): Response
    {


        return $this->render('fighter/show.html.twig', [
            'fighter' => $fighter,
            'numberOfFights' => count($fighter->getFights()),

        ]);
    }

    #[Route('/{id}/edit', name: 'app_fighter_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fighter $fighter, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FighterType::class, $fighter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fighter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fighter/edit.html.twig', [
            'fighter' => $fighter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fighter_delete', methods: ['POST'])]
    public function delete(Request $request, Fighter $fighter, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $fighter->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fighter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fighter_index', [], Response::HTTP_SEE_OTHER);
    }
}
