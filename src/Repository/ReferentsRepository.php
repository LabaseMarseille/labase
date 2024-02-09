<?php

namespace App\Repository;

use App\Entity\Referents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Referents>
 *
 * @method Referents|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referents|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referents[]    findAll()
 * @method Referents[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Referents::class);
    }

//    /**
//     * @return Referents[] Returns an array of Referents objects
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

//    public function findOneBySomeField($value): ?Referents
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
