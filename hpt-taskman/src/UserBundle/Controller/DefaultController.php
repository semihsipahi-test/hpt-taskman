<?php

namespace UserBundle\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use UserBundle\Entity\User;
use UserBundle\Repository\RoleRepository;

class DefaultController extends Controller
{
    /**
     * @Route("/create-user", name="create-user")
     */
    public function indexAction()
    {
        $roles = $this->getDoctrine()->getRepository('UserBundle:Role')->findAll();
        return $this->render('UserBundle:Default:index.html.twig', ['roles' => $roles]);
    }

    /**
     * @Route("/create-user-post", name="create-user-post")
     */
    public function createUserPostAction(Request $request)
    {
        $user = new User();

        $user->setRoleId((int)$request->request->get('role'));
        $user->setName($request->request->get('name'));
        $user->setSurname($request->request->get('surname'));
        $user->setPassword($request->request->get('password'));
        $user->setEmail($request->request->get('password'));
        $user->setIsActive($request->request->get('is_active') == "on");

        //default values
        $user->setTeamId(0);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('list-user'));
    }

    /**
     * @Route("/remove-user/{id}", name="remove-user")
     */
    public function removeUserAction($id)
    {
        $user = $this->getDoctrine()->getRepository('UserBundle:User')->find($id);
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($user);
        $em->flush();

        return $this->redirect($this->generateUrl('list-user'));
    }

    /**
     * @Route("/update-user/{id}", name="update-user")
     */
    public function updateUserAction($id)
    {
        $user = $this->getDoctrine()->getRepository('UserBundle:User')->find($id);
        $roles = $this->getDoctrine()->getRepository('UserBundle:Role')->findAll();

        return $this->render('UserBundle:Default:user-update.html.twig', ['roles' => $roles, 'user' => $user]);
    }

    /**
     * @Route("/list-user", name="list-user")
     */
    public function listUsersAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $rsm = new ResultSetMapping();

        $rsm->addEntityResult('UserBundle:User', 'u');
        $rsm->addFieldResult('u', 'id', 'id');
        $rsm->addFieldResult('u', 'username', 'username');
        $rsm->addFieldResult('u', 'surname', 'surname');
        $rsm->addFieldResult('u', 'email', 'email');
        $rsm->addFieldResult('u', 'createdDate', 'createdDate');
        $rsm->addFieldResult('u', 'isActive', 'isActive');

        $rsm->addJoinedEntityResult('UserBundle:Role', 'r', 'u', 'role');
        $rsm->addFieldResult('r', 'rolename', 'rolename');

        $sql = 'Select 
                u.id,
                u.username,
                u.surname,
                u.email,
                u.created_date,
                u.is_active,
                r.rolename
                From tm_users as u' . ' inner join tm_roles as r on u.role_id = r.id';

        $query = $em->createNativeQuery($sql, $rsm);
        $user = $query->getResult();

        return $this->render('UserBundle:Default:user-list.html.twig', ['users' => $user]);
    }
}
