<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\Role;
use UserBundle\Entity\User;



class DefaultController extends Controller
{
    /**
     * @Route("/create-user", name="create-user")
     */
    public function indexAction()
    {
        //call repository function by test user
        $roles = $this->getDoctrine()->getRepository('UserBundle:Role')->getAllRoles();

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

        //call repository function
        $this->getDoctrine()->getRepository('UserBundle:User')->addUser($user);

        return $this->redirect($this->generateUrl('list-user'));
    }

    /**
     * @Route("/remove-user/{id}", name="remove-user")
     */
    public function removeUserAction($id)
    {
        $this->getDoctrine()->getRepository('UserBundle:User')->removeUser($id);
        return $this->redirect($this->generateUrl('list-user'));
    }

    /**
     * @Route("/update-user/{id}", name="update-user")
     */
    public function updateUserAction($id)
    {
        $user = $this->getDoctrine()->getRepository('UserBundle:User')->getUser($id);
        $roles = $this->getDoctrine()->getRepository('UserBundle:Role')->getAllRoles();

        return $this->render('UserBundle:Default:user-update.html.twig', ['roles' => $roles, 'user' => $user]);
    }

    /**
     * @Route("/list-user", name="list-user")
     */
    public function listUsersAction()
    {
        $users = $this->getDoctrine()->getRepository('UserBundle:User')->getAllUsers();
        return $this->render('UserBundle:Default:user-list.html.twig', ['users' => $users]);
    }
}
