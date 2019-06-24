<?php

namespace App\Controller;

use App\Entity\School;
use App\Entity\User;
use App\Form\ProfileFormType;
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


    /**
     *
     * @Route("/escola/editar/{id}", name="school_edit", methods={"POST", "GET"})
     */
    public function edit(Request $request, School $school)
    {
        $form = $this->createForm(SchoolFormType::class, $school);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $profile =  $this->getUser();

        try{
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($school);
                $em->flush();
                $this->addFlash('success', 'Os dados de sua escola foram atualizados com sucesso!');
                return $this->redirectToRoute('profile_show', ['id' => $profile->getId()]);

            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível editar sua escola:'.$e);
        }

        return $this->render('school/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
