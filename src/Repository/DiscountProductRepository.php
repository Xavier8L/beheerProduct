<?php

namespace App\Repository;

use App\Entity\DiscountProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DiscountProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiscountProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiscountProduct[]    findAll()
 * @method DiscountProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscountProduct::class);
    }

    public function findDP($id)
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.discount','dd')
            ->where("dd.id LIKE :id")
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return DiscountProduct[] Returns an array of DiscountProduct objects
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
    public function findOneBySomeField($value): ?DiscountProduct
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
