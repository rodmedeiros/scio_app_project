<?php

namespace App\Controller;

use App\Entity\Subject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SubjectRepository;
use App\Form\SubjectType;

class SubjectController extends AbstractController
{
    /**
     * @Route("admin/disciplinas", name="subjects")
     */
    public function adminIndex(Request $request, SubjectRepository $repository)
    {

        $subjects = $repository->findAll();

        return $this->render('admin/subject/index.html.twig', [
            'subjects' => $subjects,
        ]);
    }

    /**
     * @Route("admin/disciplina/novo", name="subject_new")
     */
    public function adminNew(Request $request)
    {
        $entity = new Subject();

        $form = $this->createForm(SubjectType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Disciplina criado com sucesso!');
                return $this->redirectToRoute('subjects');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível criar a disciplina:'.$e);
        }

        return $this->render('admin/subject/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("admin/disciplina/{id}/editar", name="subject_edit")
     *
     */
    public function adminEdit(Request $request, Subject $entity)
    {
        $form = $this->createForm(SubjectType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Disciplina editada com sucesso!');
                return $this->redirectToRoute('subjects');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível editar a disciplina:'.$e);
        }

        return $this->render('admin/subject/edit.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * Deletes a Subject entity.
     *
     * @Route("subject/{id}", name="subject_delete")
     */
    public function deleteAction(Request $request, Subject $entity)
    {
        $em = $this->getDoctrine()->getManager();
        try{
            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Disciplina deletada com sucesso');
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível deletar a disciplina:'.$e);
        }

        return $this->redirectToRoute('subjects');
    }
}
