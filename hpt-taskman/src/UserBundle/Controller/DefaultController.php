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

        $qb = $em->createQueryBuilder();
        $users = $qb
            ->select(array('u', 'r'))
            ->from('UserBundle:User', 'u')
            ->join('u.role', 'r')->getQuery()->execute();

        return $this->render('UserBundle:Default:user-list.html.twig', ['users' => $users]);
    }
}
