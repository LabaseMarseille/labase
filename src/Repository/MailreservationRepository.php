<?php

namespace App\Repository;

use App\Entity\Mailreservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mailreservation>
 *
 * @method Mailreservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mailreservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mailreservation[]    findAll()
 * @method Mailreservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailreservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mailreservation::class);
    }

//    /**
//     * @return Mailreservation[] Returns an array of Mailreservation objects
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

//    public function findOneBySomeField($value): ?Mailreservation
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
