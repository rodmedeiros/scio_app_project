<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Form\ExerciseType;
use App\Repository\ContentRepository;
use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class ExerciseController extends AbstractController
{
    /**
     * @Route("/questoes", name="exercises")
     */
    public function index(Request $request, SubjectRepository $subjectRepository,
                          ContentRepository $contentRepository, Breadcrumbs $breadcrumbs
    )
    {
        //breadcrumbs
        $breadcrumbs->addItem("Questões", $this->get("router")->generate("exercises"));

        //repositories to filter
        $subjects = $subjectRepository->findAll();
        $contents = $contentRepository->findAll();

        $profileSubject = $this->getUser()->getProfile()->getSubject()->getId();

        $em = $this->getDoctrine()->getManager();

        //base query
        $entity = $em->getRepository(Exercise::class)
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

        //start with profile subject exercises
        if(!($subjects_filtered or $contents_filtered)){
            $entity = $entity-> orWhere('s.id = :val')
                ->setParameter('val', $profileSubject);
        }

        $entity = $entity->getQuery()->getResult();

        return $this->render('educational_resources/exercise/index.html.twig', [
            'subjects' => $subjects,
            'contents' => $contents,
            'exercises' => $entity,
            'profile_subject' => $profileSubject,
            'filtered_subjects' => $subjects_filtered,
            'filtered_contents' => $contents_filtered,
        ]);
    }

    /**
     * @Route("/minhas-questoes", name="profile_exercises")
     */
    public function profileIndex(Request $request, SubjectRepository $subjectRepository,
                                 ContentRepository $contentRepository, Breadcrumbs $breadcrumbs
    )
    {
        //breadcrumbs
        $breadcrumbs->addItem("Questões", $this->get("router")->generate("exercises"));
        $breadcrumbs->addItem("Minhas Questões");

        //repositories to filter
        $subjects = $subjectRepository->findAll();
        $contents = $contentRepository->findAll();

        $profile = $this->getUser()->getProfile();

        $em = $this->getDoctrine()->getManager();

        //base query
        $entity = $em->getRepository(Exercise::class)
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

        return $this->render('educational_resources/exercise/profile_index.html.twig', [
            'subjects' => $subjects,
            'contents' => $contents,
            'exercises' => $entity,
            'filtered_subjects' => $subjects_filtered,
            'filtered_contents' => $contents_filtered,
        ]);
    }


    /**
     * @Route("questao/nova", name="exercise_new")
     */
    public function new(Request $request, Breadcrumbs $breadcrumbs)
    {
        //breadcrumbs
        $breadcrumbs->addItem("Questões", $this->get("router")->generate("exercises"));
        $breadcrumbs->addItem("Sugestão de Artigo");

        $entity = new Exercise();
        $entity->setProfile($this->getUser()->getProfile());

        $form = $this->createForm(ExerciseType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Questão adicionada com sucesso!');
                return $this->redirectToRoute('exercises');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível criar questão:'.$e);
        }

        return $this->render('educational_resources/exercise/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("questao/{id}/editar", name="exercise_edit", requirements={"id"="\d+"})
     */
    public function edit(Request $request, Exercise $entity, Breadcrumbs $breadcrumbs)
    {
        //breadcrumbs
        $breadcrumbs->addItem("Questões", $this->get("router")->generate("exercises"));
        $breadcrumbs->addItem("Editar Questão");

        $entity->setProfile($this->getUser()->getProfile());

        $form = $this->createForm(ExerciseType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Questão editada com sucesso!');
                return $this->redirectToRoute('exercises');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível editar a questão:'.$e);
        }

        return $this->render('educational_resources/exercise/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
