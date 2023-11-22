<?php

namespace App\Controller;

use App\Entity\Fight;
use App\Repository\FightRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetRandomFightsByApiController extends AbstractController
{
    private $serializer;
    public function __construct(SerializerInterface $serializerInterface)
    {
        $this->serializer = $serializerInterface;
    }
    #[Route('/api/radomFights', name: "api_random_fights", methods: 'GET')]
    public function __invoke(FightRepository $fightRepository): Response
    {
        $randomFights = $fightRepository->findRandomFights(20);

        // You may need to serialize $randomFights as needed, depending on your entity structure
        $data = $this->serializer->serialize($randomFights, 'json', ['groups' => ['fight:read']]);

        return new Response($data, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
