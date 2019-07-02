<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SiteController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="site_home")
     */
    public function home(Request $request): Response
    {
        return $this->render('site/home.html.twig');
    }
}