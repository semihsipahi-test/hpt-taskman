<?php

namespace UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use UserBundle\Entity\User;

class UserRepository extends EntityRepository
{
    public function getUser($id)
    {
        if (!empty($id))
            return $this->getEntityManager()->find('UserBundle:User', $id);
    }

    public function addUser(User $user)
    {
        if (isset($user)) {
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
        }
    }

    public function removeUser($id)
    {
        if (!empty($id)) {
            $user = $this->getUser($id);
            $this->getEntityManager()->remove($user);
            $this->getEntityManager()->flush();
        }

    }

    public function getAllUsers()
    {
        return $this->getEntityManager()->createQuery("SELECT u , r FROM UserBundle:User u JOIN u.role r")->getResult();
    }
}
