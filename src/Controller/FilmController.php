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
        $breadcrumbs->addItem("Vídeos", $this->get("router")->generate("films"));

        //repositories to filter
        $subjects = $subjectRepository->findAll();
        $contents = $contentRepository->findAll();

        $profileSubject = $this->getUser()->getProfile()->getSubject()->getId();

        //initial films
        $em = $this->getDoctrine()->getManager();

        $films = $em->getRepository(Film::class)
            ->createQueryBuilder('f')
            ->join('f.content', 'c')
            ->join('c.subject', 's')
            ->orderBy('f.id', 'ASC')
        ;

        //filters
        $subjects_filtered = $request->get('subjects');
        if($subjects_filtered){
            foreach ($subjects_filtered as $subject){
                $films = $films->orWhere("s.id = '$subject'");
            }
        }

        $contents_filtered = $request->get('contents');
        if($contents_filtered){
            foreach ($contents_filtered as $content){
                $films = $films->orWhere("c.id = '$content'");
            }
        }

        //start with profile subject films
        if(!($subjects_filtered or $contents_filtered)){
            $films = $films-> orWhere('s.id = :val')
                ->setParameter('val', $profileSubject);
        }

        $films = $films->getQuery()->getResult();

        return $this->render('educational_resources/film/index.html.twig', [
            'subjects' => $subjects,
            'contents' => $contents,
            'films' => $films,
            'profile_subject' => $profileSubject,
            'filtered_subjects' => $subjects_filtered,
            'filtered_contents' => $contents_filtered,
        ]);
    }

    /**
     * @Route("/filmes/minhas-sugestoes", name="profile_films")
     */
    public function profileIndex(Request $request, SubjectRepository $subjectRepository,
                          FilmRepository $filmRepository, ContentRepository $contentRepository,
                          Breadcrumbs $breadcrumbs
    )
    {
        //breadcrumbs
        $breadcrumbs->addItem("Vídeos", $this->get("router")->generate("films"));
        $breadcrumbs->addItem("Minhas Sugestões");

        //repositories to filter
        $subjects = $subjectRepository->findAll();
        $contents = $contentRepository->findAll();

        $profile = $this->getUser()->getProfile();

        //initial films
        $em = $this->getDoctrine()->getManager();

        $films = $em->getRepository(Film::class)
            ->createQueryBuilder('f')
            ->join('f.content', 'c')
            ->join('c.subject', 's')

            ->orderBy('f.id', 'ASC')
        ;

        //filters
        $subjects_filtered = $request->get('subjects');
        if($subjects_filtered){
            foreach ($subjects_filtered as $subject){
                $films = $films->orWhere("s.id = '$subject'");
            }
        }

        $contents_filtered = $request->get('contents');
        if($contents_filtered){
            foreach ($contents_filtered as $content){
                $films = $films->orWhere("c.id = '$content'");
            }
        }

        //start with profile subject films
//        if(!($subjects_filtered or $contents_filtered)){
            $films = $films->andWhere('f.profile = :val')
                ->setParameter('val', $profile);
//        }

        $films = $films->getQuery()->getResult();

        return $this->render('educational_resources/film/profile_index.html.twig', [
            'subjects' => $subjects,
            'contents' => $contents,
            'films' => $films,
            'filtered_subjects' => $subjects_filtered,
            'filtered_contents' => $contents_filtered,
        ]);
    }


    /**
     * @Route("filme/novo", name="film_new")
     */
    public function new(Request $request, Breadcrumbs $breadcrumbs)
    {
        //breadcrumbs
        $breadcrumbs->addItem("Vídeos", $this->get("router")->generate("films"));
        $breadcrumbs->addItem("Sugestão de Vídeo");

        $entity = new Film();
        $entity->setProfile($this->getUser()->getProfile());

        $form = $this->createForm(FilmType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                //removing http and http to maintain a pattern
                $description = $form->getData()->getDescription();
                $description = str_replace("https://","",$description);
                $description = str_replace("http://","",$description);

                $entity->setDescription($description);
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
        $breadcrumbs->addItem("Vídeos", $this->get("router")->generate("films"));
        $breadcrumbs->addItem("Editar Sugestão de Vídeo");

        $entity->setProfile($this->getUser()->getProfile());

        $form = $this->createForm(FilmType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                //removing http and http to maintain a pattern
                $description = $form->getData()->getDescription();
                $description = str_replace("https://","",$description);
                $description = str_replace("http://","",$description);

                $entity->setDescription($description);
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
