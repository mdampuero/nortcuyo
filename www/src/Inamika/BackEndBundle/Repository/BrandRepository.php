<?php

namespace Inamika\BackEndBundle\Repository;

/**
 * BrandRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BrandRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAll(){
        return $this->createQueryBuilder('e')
        ->select('e')
        ->where('e.isDelete = :isDelete')
        ->setParameter('isDelete',false)
        ->orderBy("e.id","DESC");
    }

    public function search($query=null,$limit=0,$offset=0,$sort=null,$direction=null){
        if($limit>100) $limit=100;
        if($limit==0) $limit=30;
        $qb= $this->getAll()
        ->setFirstResult($offset)
        ->setMaxResults($limit)
        ->orderBy("e.id","DESC");
        if($sort){
            $qb->orderBy('e.'.$sort,$direction);
        }else{
            $qb->orderBy('RAND()');
        }
        if($query)
            $qb->andWhere('e.name LIKE :query')->setParameter('query',"%".$query."%");
        return $qb;
    }

    public function searchTotal($query=null,$limit=0,$offset=0){
        $resultTotal=$this->search($query,$limit=0,$offset=0)
        ->setFirstResult(null)
        ->select('COUNT(e.id) as total')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
        return (int)$resultTotal['total'];
    }
   
    public function total(){
        $resultTotal=$this->search()
        ->setFirstResult(null)
        ->where('e.isDelete = :isDelete')
        ->setParameter('isDelete',false)
        ->select('COUNT(e.id) as total')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
        return (int)$resultTotal['total'];
    }

}