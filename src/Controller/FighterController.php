<?php

namespace App\Controller;

use App\Entity\Fighter;
use App\Form\FighterType;
use App\Repository\FighterRepository;
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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fighter = new Fighter();
        $form = $this->createForm(FighterType::class, $fighter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
    public function show(Fighter $fighter): Response
    {
        return $this->render('fighter/show.html.twig', [
            'fighter' => $fighter,
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
        if ($this->isCsrfTokenValid('delete'.$fighter->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fighter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fighter_index', [], Response::HTTP_SEE_OTHER);
    }
}
