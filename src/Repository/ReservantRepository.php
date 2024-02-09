<?php

namespace App\Repository;

use App\Entity\Reservant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservant>
 *
 * @method Reservant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservant[]    findAll()
 * @method Reservant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservant::class);
    }

//    /**
//     * @return Reservant[] Returns an array of Reservant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservant
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
