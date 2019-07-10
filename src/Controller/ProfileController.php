<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Profile;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ProfileType;

class ProfileController extends AbstractController
{
    /**
     * List profile informations
     *
     * @Route("/perfil/{id}", name="profile_show")
     */
    public function show(Request $request, Profile $profile)
    {

        return $this->render('profile/show.html.twig', [
            'profile' => $profile,
        ]);
    }

    /**
     *
     * @Route("usuario/{id}/perfil/novo", name="profile_new", methods={"POST", "GET"})
     */
    public function new(Request $request, User $user)
    {
        $profile = new Profile();
        $profile->setUser($user);
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($profile);
                $em->flush();
                $this->addFlash('success', 'Perfil criado com sucesso!');
                return $this->redirectToRoute('site_home');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível editar seu usuário:'.$e);
        }

        return $this->render('profile/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     * @Route("/perfil/editar/{id}", name="profile_edit", methods={"POST", "GET"})
     */
    public function edit(Request $request, Profile $profile)
    {
        $form = $this->createForm(ProfileType::class, $profile);
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
    public function setProfileSchool(Request $request, Profile $profile)
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
