<?php

namespace App\Repository;

use App\Entity\Statutevent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Statutevent>
 *
 * @method Statutevent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Statutevent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Statutevent[]    findAll()
 * @method Statutevent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatuteventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statutevent::class);
    }

//    /**
//     * @return Statutevent[] Returns an array of Statutevent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Statutevent
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
