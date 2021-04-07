<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function proMax()
    {
        return $this->createQueryBuilder('c')
            ->where('c.id = 6')
            ->leftJoin('c.products','pc')
            ->leftJoin('pc.discountProducts','dpc')
            ->leftJoin('dpc.discount','ddpc')
            ->Select('MAX(ddpc.percentage) as hoogtepercentage')
            ->getQuery()->getOneOrNullResult();
    }
    public function pro()
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.products','pc')
            ->addselect('pc')
            ->getQuery()->getResult();
    }

    public function searchProduct()
    {
        return $this->createQueryBuilder('c')
            ->Join('c.products','pc')
            ->leftJoin('pc.discountProducts','dpc')
            ->leftJoin('dpc.discount','ddpc')
            ->where("ddpc.percentage = ".$this->proMax()['hoogtepercentage'])
//            ->setParameter('id','%'.$term.'%')
            ->getQuery()->getResult();
    }


    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
