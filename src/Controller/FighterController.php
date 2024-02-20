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
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/fighter')]
class FighterController extends AbstractController
{
    #[Route('/', name: 'app_fighter_index', methods: ['GET'])]
    public function index(FighterRepository $fighterRepository): Response
    {
        return $this->render('fighter/index.html.twig', [
            'fighters' => $fighterRepository->findBy(['is_valid' => true])
        ]);
    }

    #[Route('/requestedFighters', name: 'app_fighter_request', methods: ['GET'])]
    public function indexRequestFighter(FighterRepository $fighterRepository): Response
    {
        return $this->render('fighter/index_request_fighters.html.twig', [
            'newFighters' => $fighterRepository->findBy(['is_valid' => false]),
        ]);
    }

    #[Route('/new', name: 'app_fighter_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FighterRepository $fighterRepository): Response
    {
        $fighter = new Fighter();
        $fighter->setIsValid(true);
        $form = $this->createForm(FighterType::class, $fighter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($fighter);
            $entityManager->flush();

            return $this->redirectToRoute('app_fighter_generate_fight', ['id' => $fighter->getId()], Response::HTTP_SEE_OTHER);
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
    public function edit(Request $request, Fighter $fighter, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(FighterType::class, $fighter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uow = $entityManager->getUnitOfWork();
            $uow->computeChangeSets();
            $changeSet = $uow->getEntityChangeSet($fighter);
            if (isset($changeSet['strength'])) {
                $allFights = $fighter->getFights();
                foreach ($allFights as $fight) {
                    $fighter1 = $fight->getFighters()[0];
                    $fighter2 = $fight->getFighters()[1];
                    if ($fighter1 != null && $fighter2 != null) {
                        if (abs($fighter1->getStrength() - $fighter2->getStrength()) < 2) {
                            $fight->setIsBalanced(true);
                        } else {
                            $fight->setIsBalanced(false);
                        }
                    }
                }
            }
            if (!$form->get('is_valid')->getData()) {
                $allFights = $fighter->getFights();
                foreach ($allFights as $fight) {
                    $entityManager->remove($fight);
                }
            }

            $image = $form->get('image')->getData();
            if ($image) {

                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('fighter_img_dir'), // Bouger l'image dans le bon dossier que j'ai paramétré dans services.yml

                        $newFilename // nom du fichier uploadé 
                    );
                } catch (FileException $e) {
                }
                $fighter->setImage('/images/fighter/' . $newFilename); // attribution du chemin
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_fighter_show', ['id' => $fighter->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fighter/edit.html.twig', [
            'fighter' => $fighter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/generateFight', name: 'app_fighter_generate_fight')]
    public function generateFight(Fighter $fighter, FightRepository $fightRepository, EntityManagerInterface $entityManager): Response
    {
        // Generating new fighter's fights with all other fighter
        if ($fighter->isIsValid() === true && $fighter->getStrength() > 0) {
            $fightRepository->generateFightsForFighter($fighter);
        }

        return $this->redirectToRoute('app_fighter_show', ['id' => $fighter->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/removeAllFights', name: 'app_fighter_removeAllFights')]
    public function removeAllFights(Fighter $fighter, FighterRepository $fighterRepository, EntityManagerInterface $entityManager): Response
    {
        $allFights = $fighter->getFights();
        foreach ($allFights as $fight) {
            $entityManager->remove($fight);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_fighter_show', ['id' => $fighter->getId()], Response::HTTP_SEE_OTHER);
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
