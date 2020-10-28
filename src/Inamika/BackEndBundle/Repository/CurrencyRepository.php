<?php

namespace Inamika\BackEndBundle\Repository;

/**
 * CurrencyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CurrencyRepository extends \Doctrine\ORM\EntityRepository
{
    public function getUniqueNotDeleted(array $parameters){
        return $this->createQueryBuilder('e')
        ->select('e')
        ->where('e.isDelete = :isDelete')
        ->setParameter('isDelete',false)
        ->andWhere('e.name= :name')
        ->setParameter('name',$parameters['name'])
        ->setMaxResults(1)->getQuery()->getResult();
    }
    public function getAll(){
        return $this->createQueryBuilder('e')
        ->select('e')
        ->where('e.isDelete = :isDelete')
        ->setParameter('isDelete',false)
        ->orderBy("e.id","DESC");
    }
}