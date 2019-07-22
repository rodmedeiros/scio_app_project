<?php

namespace App\Controller;

use App\Entity\LessonPlain;
use App\Form\LessonPlainType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContentRepository;
use App\Repository\SubjectRepository;
use Symfony\Component\HttpFoundation\Request;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class LessonPlainController extends AbstractController
{
    /**
     * @Route("/planos-de-aula", name="lesson_plains")
     */
    public function index(Request $request, SubjectRepository $subjectRepository,
                          ContentRepository $contentRepository, Breadcrumbs $breadcrumbs
    )
    {
        //breadcrumbs
        $breadcrumbs->addItem("Planos de Aula", $this->get("router")->generate("lesson_plains"));

        //repositories to filter
        $subjects = $subjectRepository->findAll();
        $contents = $contentRepository->findAll();

        $profileSubject = $this->getUser()->getProfile()->getSubject()->getId();

        //initial lesson plains
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository(LessonPlain::class)
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

        //start with profile subject lesson plains
        if(!($subjects_filtered or $contents_filtered)){
            $entity = $entity-> orWhere('s.id = :val')
                ->setParameter('val', $profileSubject);
        }

        $entity = $entity->getQuery()->getResult();

        return $this->render('educational_resources/lesson_plain/index.html.twig', [
            'subjects' => $subjects,
            'contents' => $contents,
            'lesson_plains' => $entity,
            'profile_subject' => $profileSubject,
            'filtered_subjects' => $subjects_filtered,
            'filtered_contents' => $contents_filtered,
        ]);
    }

    /**
     * @Route("/planos-de-aula/minhas-sugestoes", name="profile_lesson_plains")
     */
    public function profileIndex(Request $request, SubjectRepository $subjectRepository,
                                 ContentRepository $contentRepository, Breadcrumbs $breadcrumbs
    )
    {
        //breadcrumbs
        $breadcrumbs->addItem("Planos de Aula", $this->get("router")->generate("lesson_plains"));
        $breadcrumbs->addItem("Minhas Sugestões");

        //repositories to filter
        $subjects = $subjectRepository->findAll();
        $contents = $contentRepository->findAll();

        $profile = $this->getUser()->getProfile();

        $em = $this->getDoctrine()->getManager();

        //base query
        $entity = $em->getRepository(LessonPlain::class)
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

        return $this->render('educational_resources/lesson_plain/profile_index.html.twig', [
            'subjects' => $subjects,
            'contents' => $contents,
            'lesson_plains' => $entity,
            'filtered_subjects' => $subjects_filtered,
            'filtered_contents' => $contents_filtered,
        ]);
    }


    /**
     * @Route("plano-de-aula/novo", name="lesson_plain_new")
     */
    public function new(Request $request, Breadcrumbs $breadcrumbs)
    {
        //breadcrumbs
        $breadcrumbs->addItem("Planos de Aula", $this->get("router")->generate("lesson_plains"));
        $breadcrumbs->addItem("Novo Plano de Aula");

        $entity = new LessonPlain();
        $entity->setProfile($this->getUser()->getProfile());

        $form = $this->createForm(LessonPlainType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Plano de aula adicionado com sucesso!');
                return $this->redirectToRoute('lesson_plains');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível criar plano de aula:'.$e);
        }

        return $this->render('educational_resources/lesson_plain/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("plano-de-aula/{id}/editar", name="lesson_plain_edit", requirements={"id"="\d+"})
     */
    public function edit(Request $request, LessonPlain $entity, Breadcrumbs $breadcrumbs)
    {
        //breadcrumbs
        $breadcrumbs->addItem("Planos de Aula", $this->get("router")->generate("lesson_plains"));
        $breadcrumbs->addItem("Editar Plano de aula");


        $form = $this->createForm(LessonPlainType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'sugestão de artigo editada com sucesso!');
                return $this->redirectToRoute('lesson_plains');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível editar sugestão de artigo:'.$e);
        }

        return $this->render('educational_resources/lesson_plain/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
