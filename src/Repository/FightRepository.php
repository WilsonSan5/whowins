<?php

namespace App\Repository;

use App\Entity\Fight;
use App\Entity\Fighter;
use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Fight>
 *
 * @method Fight|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fight|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fight[]    findAll()
 * @method Fight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FightRepository extends ServiceEntityRepository
{
    private $entityManager;
    private $fighterRepository;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, FighterRepository $fighterRepository)
    {
        parent::__construct($registry, Fight::class);
        $this->entityManager = $entityManager;
        $this->fighterRepository = $fighterRepository;
    }

    public function findRandomFights($limit): array
    {
        $allFights = $this->findBy(['is_balanced' => true]);
        // Mélanger l'ensemble des combats
        shuffle($allFights);

        // Extraire le nombre souhaité de combats
        $randomFights = array_slice($allFights, 0, $limit);
        return $randomFights;
    }

    public function generateFightsForFighter(Fighter $fighter): void
    {
        $allFighters = $this->fighterRepository->findBy(['is_valid' => true]);
        foreach ($allFighters as $fighter_2) {
            if ($fighter->getId() != $fighter_2->getId()) { // Fighter cannot fight himself
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

                $this->entityManager->persist($newFighter_vote);
                $this->entityManager->persist($fighter_2_votes);
                $this->entityManager->persist($newFight);
            }
        }
        $this->entityManager->persist($fighter);
        $this->entityManager->flush();
    }

    //    /**
//     * @return Fight[] Returns an array of Fight objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    //    public function findOneBySomeField($value): ?Fight
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
