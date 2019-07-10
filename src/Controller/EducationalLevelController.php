<?php

namespace App\Controller;

use App\Form\EducationalLevelType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EducationalLevelRepository;
use App\Entity\EducationalLevel;

class EducationalLevelController extends AbstractController
{
    /**
     * @Route("admin/segmentos", name="levels")
     */
    public function adminIndex(Request $request, EducationalLevelRepository $repository)
    {

        $levels = $repository->findAll();

        return $this->render('admin/educational_level/index.html.twig', [
            'levels' => $levels,
        ]);
    }

    /**
     * @Route("admin/segmento/novo", name="level_new")
     */
    public function adminNew(Request $request)
    {
        $entity = new EducationalLevel();

        $form = $this->createForm(EducationalLevelType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Segmento criado com sucesso!');
                return $this->redirectToRoute('levels');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível criar o segmento:'.$e);
        }

        return $this->render('admin/educational_level/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("admin/segmento/{id}/editar", name="level_edit")
     *
     */
    public function adminEdit(Request $request, EducationalLevel $entity)
    {
        $form = $this->createForm(EducationalLevelType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Segmento criada com sucesso!');
                return $this->redirectToRoute('levels');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível editar o segmento:'.$e);
        }

        return $this->render('admin/educational_level/edit.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * Deletes a EducationalLevel entity.
     *
     * @Route("level/{id}", name="level_delete")
     */
    public function deleteAction(Request $request, EducationalLevel $entity)
    {
        $em = $this->getDoctrine()->getManager();
        try{
            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Série deletada com sucesso');
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível deletar a série:'.$e);
        }

        return $this->redirectToRoute('levels');
    }
}
