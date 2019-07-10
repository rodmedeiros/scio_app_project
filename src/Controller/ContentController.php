<?php

namespace App\Controller;

use App\Entity\Content;
use App\Form\ContentType;
use App\Repository\ContentRepository;
use Doctrine\ORM\Repository\RepositoryFactory;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ContentController extends AbstractController
{
    /**
     * @Route("admin/conteudos", name="contents")
     */
    public function adminIndex(Request $request, ContentRepository $repository)
    {

        $contents = $repository->findAll();

        return $this->render('admin/content/index.html.twig', [
            'contents' => $contents,
        ]);
    }

    /**
     * @Route("admin/conteudo/novo", name="content_new")
     */
    public function adminNew(Request $request)
    {
        $content = new Content();

        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($content);
                $em->flush();
                $this->addFlash('success', 'conteúdo criado com sucesso!');
                return $this->redirectToRoute('contents');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível criar conteúdo:'.$e);
        }

        return $this->render('admin/content/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("admin/conteudo/{id}/editar", name="content_edit")
     */
    public function adminEdit(Request $request, Content $entity)
    {
        $form = $this->createForm(ContentType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Conteúdo criado com sucesso!');
                return $this->redirectToRoute('contents');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível editar o conteúdo:'.$e);
        }

        return $this->render('admin/content/edit.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * Deletes a content entity.
     *
     * @Route("content/{id}", name="content_delete")
     */
    public function deleteAction(Request $request, Content $entity)
    {
        $em = $this->getDoctrine()->getManager();
        try{
            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Conteúdo deletado com sucesso');
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível deletar o conteúdo:'.$e);
        }

        return $this->redirectToRoute('contents');
    }

}
