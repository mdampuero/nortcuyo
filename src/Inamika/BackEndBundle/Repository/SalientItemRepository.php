<?php

namespace Inamika\BackEndBundle\Repository;

/**
 * SalientItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SalientItemRepository extends \Doctrine\ORM\EntityRepository
{
    public function removeBySalient($salient){
        $em = $this->getEntityManager();
        
        $delete = $em->createQuery('delete InamikaBackEndBundle:SalientItem e WHERE e.salient = \''.$salient->getId().'\'');
        $delete->execute();
        
    }
}
