<?php

namespace Inamika\BackEndBundle\Repository;

/**
 * PreformattedItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PreformattedItemRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAll(){
        return $this->createQueryBuilder('e')
        ->select('e')
        ->join('e.product','product')
        ->where('product.isDelete = :isDelete')
        ->setParameter('isDelete',false)
        ->orderBy("e.position","ASC");
    }

    public function search($query=null,$limit=0,$offset=0,$sort=null,$direction=null){
        if($limit>100) $limit=100;
        if($limit==0) $limit=30;
        $qb= $this->getAll()
        ->setFirstResult($offset);
        if($limit!="-1")
            $qb->setMaxResults($limit);

        $qb->orderBy("product.id","DESC");
        if($sort){
            if($sort=="position") $sort="e.position";
            $qb->orderBy($sort,$direction);
        }else{
            $qb->orderBy('RAND()');
        }
        if($query){
            $words=explode(" ",$query);
            if(count($words)>1){
                foreach ($words as $key => $word) {
                    $queryString=array();
                    $queryString[]="CONCAT(product.code,product.name,COALESCE(product.description,''),COALESCE(product.tags,''),COALESCE(product.barcode,'')) LIKE :word".$key;
                    $qb->setParameter('word'.$key,"%".$word."%");
                    $qb->andWhere(join(' AND ',$queryString));
                }
            }else{
                $qb->andWhere("CONCAT(product.code,product.name,COALESCE(product.description,''),COALESCE(product.tags,''),COALESCE(product.barcode,'')) LIKE :query")->setParameter('query',"%".$query."%");
            }
        }
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
        ->where('product.isDelete = :isDelete')
        ->setParameter('isDelete',false)
        ->select('COUNT(product.id) as total')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
        return (int)$resultTotal['total'];
    }

    public function removeAll(){
        $em = $this->getEntityManager();
        $delete = $em->createQuery('delete from InamikaBackEndBundle:PreformattedItem e');
        $delete->execute();        
    }

}
