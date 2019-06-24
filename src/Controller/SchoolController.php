<?php

namespace App\Controller;

use App\Entity\School;
use App\Form\SchoolFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SchoolController extends AbstractController
{
    /**
     * @Route("/school", name="school")
     */
    public function index()
    {
        return $this->render('school/index.html.twig', [
            'controller_name' => 'SchoolController',
        ]);
    }

    /**
     * @Route("/escola/new", name="school_new")
     */
    public function new(Request $request)
    {
        $school = new School();

        $form = $this->createForm(SchoolFormType::class, $school);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $profile =  $this->getUser();

        try{
            if ($form->isSubmitted()) {
                $em->persist($school);
                $em->flush();
                $this->addFlash('success', 'Escola definida com sucesso!');
                return $this->redirectToRoute('profile_show', ['id' => $profile->getId()]);

            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível inserir sua escola:'.$e);
        }

        return $this->render('school/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
