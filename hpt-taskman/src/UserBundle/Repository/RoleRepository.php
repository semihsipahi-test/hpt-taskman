<?php

namespace UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RoleRepository extends EntityRepository
{
    public function getAllRoles()
    {
        return $this->getEntityManager()->createQuery('SELECT R FROM UserBundle:Role R')->getResult();
    }
}
