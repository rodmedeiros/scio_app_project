<?php

namespace App\Controller;

use App\Entity\Content;
use App\Form\TeachingResourcesType;
use App\Repository\ContentRepository;
use App\Repository\FilmRepository;
use App\Repository\SubjectRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Film;
use App\Form\FilmType;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class FilmController extends AbstractController
{
    /**
     * @Route("/filmes", name="films")
     */
    public function index(Request $request, SubjectRepository $subjectRepository,
                          FilmRepository $filmRepository, ContentRepository $contentRepository,
                          Breadcrumbs $breadcrumbs
                        )
    {
        //breadcrumbs
        $breadcrumbs->addItem("Filmes", $this->get("router")->generate("films"));

        $subjects = $subjectRepository->findAll();

        $contents = [];
        $subject_filtered = $request->get('subject');
        if($subject_filtered){
            $contents = $contentRepository->findBy(['subject' => $subject_filtered]);
        }

        $content_filtered = $request->get('content');
        if($content_filtered){
            $films = $filmRepository->findBy(['content' => $content_filtered]);
        }else{
            $profile_subject = $this->getUser()->getProfile()->getSubject()->getId();
            $films = $filmRepository->findBySubjectField($profile_subject);
        }

        return $this->render('educational_resources/film/index.html.twig', [
            'subjects' => $subjects,
            'contents' => $contents,
            'films' => $films,
        ]);
    }

    /**
     * @Route("filme/novo", name="film_new")
     */
    public function new(Request $request, Breadcrumbs $breadcrumbs)
    {
        //breadcrumbs
        $breadcrumbs->addItem("Filmes", $this->get("router")->generate("films"));
        $breadcrumbs->addItem("Sugestão de Vídeo");

        $entity = new Film();
        $entity->setProfile($this->getUser()->getProfile());

        $form = $this->createForm(FilmType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'sugestão de filme adicionada com sucesso!');
                return $this->redirectToRoute('films');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível criar sugestão de filme:'.$e);
        }

        return $this->render('educational_resources/film/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("filme/{id}/editar", name="film_edit")
     */
    public function edit(Request $request, Film $entity, Breadcrumbs $breadcrumbs)
    {
        //breadcrumbs
        $breadcrumbs->addItem("Filmes", $this->get("router")->generate("films"));
        $breadcrumbs->addItem("Editar Sugestão de Vídeo");

        $entity->setProfile($this->getUser()->getProfile());

        $form = $this->createForm(FilmType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'sugestão de filme editada com sucesso!');
                return $this->redirectToRoute('films');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível editar sugestão de filme:'.$e);
        }

        return $this->render('educational_resources/film/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
