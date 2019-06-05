<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        return $this->render('./default/index.html.twig');
    }

    /**
     * @Route("/app{token}", name="front_view", requirements={"token"=".+"})
     */
    public function frontView($token)
    {
        return $this->render('./default/index.html.twig');
    }
}
