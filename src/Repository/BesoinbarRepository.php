<?php

namespace App\Repository;

use App\Entity\Besoinbar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Besoinbar>
 *
 * @method Besoinbar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Besoinbar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Besoinbar[]    findAll()
 * @method Besoinbar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BesoinbarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Besoinbar::class);
    }

//    /**
//     * @return Besoinbar[] Returns an array of Besoinbar objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Besoinbar
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
