<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Form\GradeType;
use App\Repository\GradeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GradeController extends AbstractController
{
    /**
     * @Route("admin/series", name="grades")
     */
    public function adminIndex(Request $request, GradeRepository $repository)
    {

        $grades = $repository->findAll();

        return $this->render('admin/grade/index.html.twig', [
            'grades' => $grades,
        ]);
    }

    /**
     * @Route("admin/serie/nova", name="grade_new")
     */
    public function adminNew(Request $request)
    {
        $entity = new Grade();

        $form = $this->createForm(GradeType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Série criada com sucesso!');
                return $this->redirectToRoute('grades');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível criar a série:'.$e);
        }

        return $this->render('admin/grade/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("admin/serie/{id}/editar", name="grade_edit")
     *
     */
    public function adminEdit(Request $request, Grade $entity)
    {
        $form = $this->createForm(GradeType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Série criada com sucesso!');
                return $this->redirectToRoute('grades');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível editar a série:'.$e);
        }

        return $this->render('admin/grade/edit.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * Deletes a grade entity.
     *
     * @Route("grade/{id}", name="grade_delete")
     */
    public function deleteAction(Request $request, Grade $entity)
    {
        $em = $this->getDoctrine()->getManager();
        try{
            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Série deletada com sucesso');
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível deletar a série:'.$e);
        }

        return $this->redirectToRoute('grades');
    }


}
