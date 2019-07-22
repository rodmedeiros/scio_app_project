<?php

namespace App\Controller;

use App\Entity\PedagogicalProject;
use App\Form\PedagogicalProjectType;
use App\Repository\ContentRepository;
use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class PedagogicalProjectController extends AbstractController
{
    /**
     * @Route("/Projetos", name="projects")
     */
    public function index(Request $request, SubjectRepository $subjectRepository,
                          ContentRepository $contentRepository, Breadcrumbs $breadcrumbs
    )
    {
        //breadcrumbs
        $breadcrumbs->addItem("Projetos Pedagógicos", $this->get("router")->generate("projects"));

        //repositories to filter
        $subjects = $subjectRepository->findAll();
        $contents = $contentRepository->findAll();

        $profileSubject = $this->getUser()->getProfile()->getSubject()->getId();

        //initial project
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository(PedagogicalProject::class)
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

        //start with profile subject projects
        if(!($subjects_filtered or $contents_filtered)){
            $entity = $entity-> orWhere('s.id = :val')
                ->setParameter('val', $profileSubject);
        }

        $entity = $entity->getQuery()->getResult();

        return $this->render('educational_resources/pedagogical_project/index.html.twig', [
            'subjects' => $subjects,
            'contents' => $contents,
            'projects' => $entity,
            'profile_subject' => $profileSubject,
            'filtered_subjects' => $subjects_filtered,
            'filtered_contents' => $contents_filtered,
        ]);
    }

    /**
     * @Route("/projetos/minhas-sugestoes", name="profile_projects")
     */
    public function profileIndex(Request $request, SubjectRepository $subjectRepository,
                                 ContentRepository $contentRepository, Breadcrumbs $breadcrumbs
    )
    {
        //breadcrumbs
        $breadcrumbs->addItem("Projetos Pedagógicos", $this->get("router")->generate("projects"));
        $breadcrumbs->addItem("Minhas Sugestões");

        //repositories to filter
        $subjects = $subjectRepository->findAll();
        $contents = $contentRepository->findAll();

        $profile = $this->getUser()->getProfile();

        $em = $this->getDoctrine()->getManager();

        //base query
        $entity = $em->getRepository(PedagogicalProject::class)
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

        return $this->render('educational_resources/pedagogical_project/profile_index.html.twig', [
            'subjects' => $subjects,
            'contents' => $contents,
            'projects' => $entity,
            'filtered_subjects' => $subjects_filtered,
            'filtered_contents' => $contents_filtered,
        ]);
    }


    /**
     * @Route("projetos/novo", name="project_new")
     */
    public function new(Request $request, Breadcrumbs $breadcrumbs)
    {
        //breadcrumbs
        $breadcrumbs->addItem("Projetos Pedagógicos", $this->get("router")->generate("projects"));
        $breadcrumbs->addItem("Novo Projeto Pedagógico");

        $entity = new PedagogicalProject();
        $entity->setProfile($this->getUser()->getProfile());

        $form = $this->createForm(PedagogicalProjectType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Projeto Pedagógico adicionado com sucesso!');
                return $this->redirectToRoute('projects');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível criar projeto pedagógico:'.$e);
        }

        return $this->render('educational_resources/pedagogical_project/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("projeto/{id}/editar", name="project_edit", requirements={"id"="\d+"})
     */
    public function edit(Request $request, PedagogicalProject $entity, Breadcrumbs $breadcrumbs)
    {
        //breadcrumbs
        $breadcrumbs->addItem("Projeto Pedagógico", $this->get("router")->generate("projects"));
        $breadcrumbs->addItem("Editar Projeto Pedagógico");


        $form = $this->createForm(PedagogicalProjectType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Projeto Pedagógico editado com sucesso!');
                return $this->redirectToRoute('projects');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível editar projeto pedagógico:'.$e);
        }

        return $this->render('educational_resources/pedagogical_project/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
