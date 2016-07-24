<?php

namespace MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/main", name="main-page")
     */
    public function indexAction()
    {
        return $this->render('MainBundle:Default:index.html.twig');
    }
}
