<?php

namespace App\Repository;

use App\Entity\Typereservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Typereservation>
 *
 * @method Typereservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typereservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typereservation[]    findAll()
 * @method Typereservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypereservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typereservation::class);
    }

//    /**
//     * @return Typereservation[] Returns an array of Typereservation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Typereservation
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
