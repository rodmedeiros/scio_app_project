<?php

namespace App\Controller;

use App\Repository\ContentRepository;
use App\Repository\FilmRepository;
use App\Repository\SubjectRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Paper;
use App\Form\PaperType;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class PaperController extends AbstractController
{
    /**
     * @Route("/artigos", name="papers")
     */
    public function index(Request $request, SubjectRepository $subjectRepository,
                          ContentRepository $contentRepository, Breadcrumbs $breadcrumbs
    )
    {
        //breadcrumbs
        $breadcrumbs->addItem("Artigos", $this->get("router")->generate("papers"));

        //repositories to filter
        $subjects = $subjectRepository->findAll();
        $contents = $contentRepository->findAll();

        $profileSubject = $this->getUser()->getProfile()->getSubject()->getId();

        //initial films
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository(Paper::class)
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

        //start with profile subject papers
        if(!($subjects_filtered or $contents_filtered)){
            $entity = $entity-> orWhere('s.id = :val')
                ->setParameter('val', $profileSubject);
        }

        $entity = $entity->getQuery()->getResult();

        return $this->render('educational_resources/paper/index.html.twig', [
            'subjects' => $subjects,
            'contents' => $contents,
            'papers' => $entity,
            'profile_subject' => $profileSubject,
            'filtered_subjects' => $subjects_filtered,
            'filtered_contents' => $contents_filtered,
        ]);
    }

    /**
     * @Route("/artigos/minhas-sugestoes", name="profile_papers")
     */
    public function profileIndex(Request $request, SubjectRepository $subjectRepository,
                                 ContentRepository $contentRepository, Breadcrumbs $breadcrumbs
    )
    {
        //breadcrumbs
        $breadcrumbs->addItem("Arigos", $this->get("router")->generate("papers"));
        $breadcrumbs->addItem("Minhas Sugestões");

        //repositories to filter
        $subjects = $subjectRepository->findAll();
        $contents = $contentRepository->findAll();

        $profile = $this->getUser()->getProfile();

        $em = $this->getDoctrine()->getManager();

        //base query
        $entity = $em->getRepository(Paper::class)
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

        return $this->render('educational_resources/paper/profile_index.html.twig', [
            'subjects' => $subjects,
            'contents' => $contents,
            'papers' => $entity,
            'filtered_subjects' => $subjects_filtered,
            'filtered_contents' => $contents_filtered,
        ]);
    }


    /**
     * @Route("artigo/novo", name="paper_new")
     */
    public function new(Request $request, Breadcrumbs $breadcrumbs)
    {
        //breadcrumbs
        $breadcrumbs->addItem("Artigos", $this->get("router")->generate("papers"));
        $breadcrumbs->addItem("Sugestão de Artigo");

        $entity = new Paper();
        $entity->setProfile($this->getUser()->getProfile());

        $form = $this->createForm(PaperType::class, $entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        try{
            if ($form->isSubmitted() && $form->isValid()){
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'sugestão de artigo adicionada com sucesso!');
                return $this->redirectToRoute('films');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível criar sugestão de artigo:'.$e);
        }

        return $this->render('educational_resources/paper/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("artigo/{id}/editar", name="paper_edit", requirements={"id"="\d+"})
     */
    public function edit(Request $request, Paper $entity, Breadcrumbs $breadcrumbs)
    {
        //breadcrumbs
        $breadcrumbs->addItem("Vídeos", $this->get("router")->generate("films"));
        $breadcrumbs->addItem("Editar Sugestão de Vídeo");

        $entity->setProfile($this->getUser()->getProfile());

        $form = $this->createForm(PaperType::class, $entity);
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
                $this->addFlash('success', 'sugestão de artigo editada com sucesso!');
                return $this->redirectToRoute('papers');
            }
        }catch (\Exception $e){
            $this->addFlash('error', 'Não foi possível editar sugestão de artigo:'.$e);
        }

        return $this->render('educational_resources/paper/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
