<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * GameRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GameRepository extends EntityRepository
{
    public function selectAll(){
        $query=$this->getEntityManager()->createQueryBuilder()
            ->select('p')
            ->from('AppBundle:Game','p')
            ->getQuery();

        $products = $query->getResult();
        return $products;
    }
}
