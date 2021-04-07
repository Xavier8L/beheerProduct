<?php

namespace App\Repository;

use App\Entity\Discount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Discount|null find($id, $lockMode = null, $lockVersion = null)
 * @method Discount|null findOneBy(array $criteria, array $orderBy = null)
 * @method Discount[]    findAll()
 * @method Discount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Discount::class);
    }

    public function findCode($code)
    {
        return $this->createQueryBuilder('d')
            ->where('d.code= :code')
            ->setParameter('code', $code)
            ->getQuery()->getOneOrNullResult();
    }



//    public function findDiscount($id)
//    {
//        return $this->createQueryBuilder('d')
////            ->select('max(d.percentage)')
//            ->leftJoin('d.discountProducts', 'dp')
//            ->where('dp.product = :id')
//            ->setParameter('id', $id)
//            ->getQuery()->getOneOrNullResult();
//    }

//    public function highest() {
//        return $this->createQueryBuilder('d')
//            ->select('max(d.percentage) as percentage')
//            ->getQuery()->getOneOrNullResult();
//    }

    // /**
    //  * @return Discount[] Returns an array of Discount objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Discount
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
