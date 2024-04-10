<?php

namespace App\Repository;

use App\Entity\Twouit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Twouit>
 *
 * @method Twouit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Twouit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Twouit[]    findAll()
 * @method Twouit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TwouitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Twouit::class);
    }

//    /**
//     * @return Twouit[] Returns an array of Twouit objects
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

//    public function findOneBySomeField($value): ?Twouit
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
