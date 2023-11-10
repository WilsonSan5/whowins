<?php

namespace App\Controller;

use App\Repository\FightRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GetRandomFightController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function __invoke(FightRepository $fightRepository)
    {
        $randomFights = $fightRepository->findAll();
        shuffle($randomFights);
        return $randomFights;
    }
}