<?php

namespace Inamika\BackEndBundle\Repository;

/**
 * ProductsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductsRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAll(){
        return $this->createQueryBuilder('e')
        ->select('e')
        ->where('e.isDelete = :isDelete')
        ->setParameter('isDelete',false)
        ->orderBy("e.id","DESC");
    }

    public function setUpdate(){
        $em = $this->getEntityManager();
        
        $delete = $em->createQuery('update InamikaBackEndBundle:Products e set e.isDelete = 1 WHERE e.isUpdate = 0');
        $delete->execute();
        
        $update = $em->createQuery('update InamikaBackEndBundle:Products e set e.isUpdate = 0');
        $update->execute();
        
    }

    public function search($query=null,$limit=0,$offset=0,$sort=null,$direction=null){

        if($limit>100) $limit=100;
        if($limit==0) $limit=30;
        $qb= $this->getAll()
        ->setFirstResult($offset)
        ->setMaxResults($limit);
        if($sort){
            if ($sort=='4' || $sort=='3') 
                $sort='price';
            $qb->orderBy('e.'.$sort,$direction);
        }else{
            $qb->orderBy('RAND('.$direction.')');
        }
        if($query){
            $words=explode(" ",$query);
            if(count($words)>1){
                foreach ($words as $key => $word) {
                    $queryString=array();
                    $queryString[]="CONCAT(e.code,e.name,COALESCE(e.description,''),COALESCE(e.tags,''),COALESCE(e.barcode,'')) LIKE :word".$key;
                    $qb->setParameter('word'.$key,"%".$word."%");
                    $qb->andWhere(join(' AND ',$queryString));
                }
            }else{
                $qb->andWhere("CONCAT(e.code,e.name,COALESCE(e.description,''),COALESCE(e.tags,''),COALESCE(e.barcode,'')) LIKE :query")->setParameter('query',"%".$query."%");
            }
        }
        return $qb;
    }

    public function getRelated($code){
        $entity=$this->findOneByCode($code);
        return $qb= $this->getAll()
        ->setMaxResults(10)
        ->andWhere("e.category = :category")->setParameter('category',$entity->getCategory())
        ->andWhere("e.id <> :id")->setParameter('id',$entity->getId())
        ->orderBy('RAND()');
    }
    
    public function getByCategory($category,$limit=0,$offset=0,$sort=null,$direction=null){

        if($limit>100) $limit=100;
        if($limit==0) $limit=30;
        $qb= $this->getAll()
        ->setFirstResult($offset)
        ->setMaxResults($limit);
        if($sort){
            $qb->orderBy('e.'.$sort,$direction);
        }else{
            $qb->orderBy('RAND('.$direction.')');
        }
        $qb->andWhere("e.category = :category")->setParameter('category',$category);
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
    
    public function getByCategoryTotal($category,$limit=0,$offset=0){
        $resultTotal=$this->getByCategory($category,$limit=0,$offset=0)
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

    public function getUniqueNotDeleted(array $parameters){
        return $this->createQueryBuilder('e')
        ->select('e')
        ->where('e.isDelete = :isDelete')
        ->setParameter('isDelete',false)
        ->andWhere('e.code= :code')
        ->setParameter('code',$parameters['code'])
        ->setMaxResults(1)->getQuery()->getResult();
    }
}
