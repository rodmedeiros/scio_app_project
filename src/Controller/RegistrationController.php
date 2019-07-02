<?php

//namespace App\Controller;
//
//use App\Entity\User;
//use App\Form\RegistrationFormType;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//
//class RegistrationController extends AbstractController
//{
//    /**
//     * @Route("/registrar", name="scio_app_register")
//     */
//    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
//    {
//        $user = new User();
//        $form = $this->createForm(RegistrationFormType::class, $user);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            // encode the plain password
//            $user->setPassword(
//                $passwordEncoder->encodePassword(
//                    $user,
//                    $form->get('plainPassword')->getData()
//                )
//            );
//
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($user);
//            $entityManager->flush();
//
//            // do anything else you need here, like send an email
//
//
//            return $this->redirectToRoute('site_index');
//        } else {
//            $this->addFlash('error', 'Erro ao cadastrar usuário');
//        }
//
//        return $this->render('registration/register.html.twig', [
//            'form' => $form->createView(),
//        ]);
//    }
//}

// src/Controller/RegistrationController.php
namespace App\Controller;

//use App\Entity\User;
//use App\Form\RegistrationFormType;
//use App\Security\StubAuthenticator;
//use mysql_xdevapi\Exception;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
//
//
//class RegistrationController extends AbstractController
//{
//    /**
//     * @Route("/registrar", name="scio_app_register")
//     */
//    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
//    {
//        $user = new User();
//        $form = $this->createForm(RegistrationFormType::class, $user);
//        $form->handleRequest($request);
//
//        try{
//            if ($form->isSubmitted() ) {
//                // encode the plain password
//                $user->setPassword(
//                    $passwordEncoder->encodePassword(
//                        $user,
//                        $form->get('plainPassword')->getData()
//                    )
//                );
//                $user->setPlainPassword(
//                    $passwordEncoder->encodePassword(
//                        $user,
//                        $form->get('plainPassword')->getData()
//                    )
//                );
//
//                $entityManager = $this->getDoctrine()->getManager();
//                $entityManager->persist($user);
//                $entityManager->flush();
//
//                // do anything else you need here, like send an email
//
//                return $this->redirectToRoute('site_home');
//            }
//        } catch (\Exception $e) {
//            $this->addFlash('error', $e->getMessage());
//        }
//
//
//        return $this->render('registration/register.html.twig', [
//            'form' => $form->createView(),
//        ]);
//    }

// src/Controller/RegistrationController.php
namespace App\Controller;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\StubAuthenticator;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/registrar", name="scio_app_register",  methods={"POST", "GET"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        try{
            if ($form->isSubmitted()) {
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                // do anything else you need here, like send an email
                return $this->redirectToRoute('site_home');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}