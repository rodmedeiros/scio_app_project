<?php

namespace App\Controller;

use App\Form\AddSchoolToProfileType;
use App\Form\ProfileFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;


/**
 * ProfileController
 */
Class ProfileController extends AbstractController
{
    /**
     * List profile informations
     *
     * @Route("/perfil/{id}", name="profile_show")
     */
    public function show(Request $request, User $profile)
    {

        return $this->render('profile/show.html.twig', [
            'profile' => $profile,
        ]);
    }

    /**
     * List profile informations
     *
     * @Route("/perfil/editar/{id}", name="profile_edit", methods={"POST", "GET"})
     */
    public function edit(Request $request, User $profile)
    {
        $form = $this->createForm(ProfileFormType::class, $profile);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($profile);
                $em->flush();
                $this->addFlash('success', 'Perfil atualizado com sucesso!');
                return $this->redirectToRoute('profile_show', ['id' => $profile->getId()]);

            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível editar seu usuário:'.$e);
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * List profile informations
     *
     * @Route("/perfil/add_escola/{id}", name="profile_school", methods={"POST", "GET"})
     */
    public function setProfileSchool(Request $request, User $profile)
    {
        $form = $this->createForm(AddSchoolToProfileType::class, $profile);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($profile);
                $em->flush();
                $this->addFlash('success', 'Escola definida com sucesso!');
                return $this->redirectToRoute('profile_show', ['id' => $profile->getId()]);

            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível inserir sua escola:'.$e);
        }

        return $this->render('profile/add_school.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}