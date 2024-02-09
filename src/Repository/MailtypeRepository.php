<?php

namespace App\Repository;

use App\Entity\Mailtype;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mailtype>
 *
 * @method Mailtype|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mailtype|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mailtype[]    findAll()
 * @method Mailtype[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailtypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mailtype::class);
    }

//    /**
//     * @return Mailtype[] Returns an array of Mailtype objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Mailtype
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
