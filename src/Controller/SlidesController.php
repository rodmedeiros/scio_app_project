<?php

namespace App\Controller;

use App\Entity\Slides;
use App\Form\SlidesType;
use App\Repository\ContentRepository;
use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class SlidesController extends AbstractController
{
    /**
     * @Route("/slides", name="slides")
     */
    public function index(Request $request, SubjectRepository $subjectRepository,
                          ContentRepository $contentRepository, Breadcrumbs $breadcrumbs
    )
    {
        //breadcrumbs
        $breadcrumbs->addItem("Slides", $this->get("router")->generate("slides"));

        //repositories to filter
        $subjects = $subjectRepository->findAll();
        $contents = $contentRepository->findAll();

        $profileSubject = $this->getUser()->getProfile()->getSubject()->getId();

        //initial slide
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository(Slides::class)
            ->createQueryBuilder('f')
            ->join('f.content', 'c')
            ->join('c.subject', 's')
            ->orderBy('f.id', 'ASC')
        ;

        //filters
        $subjects_filtered = $request->get('subjects');
        if($subjects_filtered){
            foreach ($subjects_filtered as $subject){
                $entity = $entity->orWhere("s.id = '$subject'");
            }
        }

        $contents_filtered = $request->get('contents');
        if($contents_filtered){
            foreach ($contents_filtered as $content){
                $entity = $entity->orWhere("c.id = '$content'");
            }
        }

        //start with profile subject slides
        if(!($subjects_filtered or $contents_filtered)){
            $entity = $entity-> orWhere('s.id = :val')
                ->setParameter('val', $profileSubject);
        }

        $entity = $entity->getQuery()->getResult();

        return $this->render('educational_resources/slides/index.html.twig', [
            'subjects' => $subjects,
            'contents' => $contents,
            'slides' => $entity,
            'profile_subject' => $profileSubject,
            'filtered_subjects' => $subjects_filtered,
            'filtered_contents' => $contents_filtered,
        ]);
    }

    /**
     * @Route("/slides/minhas-sugestoes", name="profile_slides")
     */
    public function profileIndex(Request $request, SubjectRepository $subjectRepository,
                                 ContentRepository $contentRepository, Breadcrumbs $breadcrumbs
    )
    {
        //breadcrumbs
        $breadcrumbs->addItem("Slides", $this->get("router")->generate("slides"));
        $breadcrumbs->addItem("Minhas Sugestões");

        //repositories to filter
        $subjects = $subjectRepository->findAll();
        $contents = $contentRepository->findAll();

        $profile = $this->getUser()->getProfile();

        $em = $this->getDoctrine()->getManager();

        //base query
        $entity = $em->getRepository(Slides::class)
            ->createQueryBuilder('f')
            ->join('f.content', 'c')
            ->join('c.subject', 's')
            ->orderBy('f.id', 'ASC')
        ;

        //filters
        $subjects_filtered = $request->get('subjects');
        if($subjects_filtered){
            foreach ($subjects_filtered as $subject){
                $entity = $entity->orWhere("s.id = '$subject'");
            }
        }

        $contents_filtered = $request->get('contents');
        if($contents_filtered){
            foreach ($contents_filtered as $content){
                $entity = $entity->orWhere("c.id = '$content'");
            }
        }

        //only profile resources
        $entity = $entity->andWhere('f.profile = :val')
            ->setParameter('val', $profile);

        $entity = $entity->getQuery()->getResult();

        return $this->render('educational_resources/slides/profile_index.html.twig', [
            'subjects' => $subjects,
            'contents' => $contents,
            'slides' => $entity,
            'filtered_subjects' => $subjects_filtered,
            'filtered_contents' => $contents_filtered,
        ]);
    }


    /**
     * @Route("slide/novo", name="slide_new")
     */
    public function new(Request $request, Breadcrumbs $breadcrumbs)
    {
        //breadcrumbs
        $breadcrumbs->addItem("Slides", $this->get("router")->generate("slides"));
        $breadcrumbs->addItem("Novo Slide");

        $entity = new Slides();
        $entity->setProfile($this->getUser()->getProfile());

        $form = $this->createForm(SlidesType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Slide adicionado com sucesso!');
                return $this->redirectToRoute('slides');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível criar o slide:'.$e);
        }

        return $this->render('educational_resources/slides/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("slides/{id}/editar", name="slide_edit", requirements={"id"="\d+"})
     */
    public function edit(Request $request, Slides $entity, Breadcrumbs $breadcrumbs)
    {
        //breadcrumbs
        $breadcrumbs->addItem("Slides", $this->get("router")->generate("slides"));
        $breadcrumbs->addItem("Editar Slide");


        $form = $this->createForm(SlidesType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Slide editado com sucesso!');
                return $this->redirectToRoute('slides');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível editar o slide:'.$e);
        }

        return $this->render('educational_resources/slides/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
