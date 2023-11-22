<?php

namespace App\Repository;

use App\Entity\Fight;
use App\Entity\Fighter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fight::class);
    }

    public function findRandomFights($limit): array
    {
        $allFights = $this->findAll();
        // Mélanger l'ensemble des combats
        shuffle($allFights);

        // Extraire le nombre souhaité de combats
        $randomFights = array_slice($allFights, 0, $limit);
        return $randomFights;
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
