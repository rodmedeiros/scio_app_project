<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="user_index")
     */
    public function index(Request $request): Response
    {
        $user = 'Rodrigo';
        return $this->render('core_bundle/user/index.html.twig', [
            'user' => $user,
        ]);
    }
}