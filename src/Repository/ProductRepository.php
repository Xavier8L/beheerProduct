<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

//    public function findName()
//    {
//        $dql ="SELECT product FROM App\Entity\Product product ORDER BY product.name DESC";
//        $query = $this->getEntityManager()->createQuery($dql);
//
//        return $query->execute();
//    }
//
    public function findCategory($categoryId)
    {
        return  $this->createQueryBuilder('p')
            ->leftJoin('p.category','pc')
            ->where("pc.id LIKE :categoryId")
            ->setParameter('categoryId',$categoryId)
            ->getQuery()->getResult();
    }

    public function findCategoryParent($pId)
    {
        return  $this->createQueryBuilder('p')
            ->leftJoin('p.category','pc')
            ->leftJoin('pc.parent','pcp')
            ->where("pcp.id LIKE :pId")
            ->setParameter('pId',$pId)
            ->getQuery()->getResult();
    }
    public function Search($key)
    {
        return  $this->createQueryBuilder('p')
            ->where("p.name LIKE :key")
            ->setParameter('key',"%".$key."%")
            ->getQuery()->getResult();
    }

//    public function max($categoryId)
//    {
//        return $this->createQueryBuilder('p')
//            ->leftJoin('p.category','cp')
//            ->where('cp.id =:categoryId')
//            ->setParameter('categoryId',$categoryId)
//            ->leftJoin('p.discountProducts','dp')
//            ->leftJoin('dp.discount','ddp')
//            ->Select('MAX(ddp.percentage) as hoogtepercentage')
//            ->getQuery()->getOneOrNullResult();
//    }
//
//    public function category($categoryId)
//    {
//        return $this->createQueryBuilder('p')
//            ->leftJoin('p.category','cp')
//            ->where('cp.id =:categoryId')
//            ->setParameter('categoryId',$categoryId)
//            ->leftJoin('p.discountProducts','dp')
//            ->leftJoin('dp.discount','ddp')
//            ->andwhere("ddp.percentage = ".$this->max($categoryId)['hoogtepercentage'])
//            ->addSelect('ddp.percentage')
//            ->getQuery()->getOneOrNullResult();
//    }
//
//    public function hoogtKortingProduct()
//    {
//
//    }
//
////
//
//    public function searchJoin()
//    {
//        return $this->createQueryBuilder('p')
//            ->Join('p.discountProducts','dp')
//            ->Join('dp.discount','ddp')
//            ->where("ddp.percentage = ".$this->max()['hoogtepercentage'])
////            ->setParameter('id','%'.$term.'%')
//            ->getQuery()->getResult();
//    }
//
//    public function search($term)
//    {
//        return $this->createQueryBuilder('p')
//            ->where('p.name LIKE :searchTerm')
//            ->setParameter('searchTerm', '%'.$term.'%')
//            ->getQuery()->getResult();
//    }
    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
