<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Dashboard controller.
 */
class DefaultController extends AbstractController
{

    /**
     * Lists all entities.
     *
     * @Route("dashboard", name="dashboard")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

//        $competence = ($request->query->has('competence')) ? $request->query->get('competence') : date('m/Y');
//        $competence = \DateTime::createFromFormat('m/Y', $competence);

        // breadcrumb
//        $breadcrumbs = $this->get("white_october_breadcrumbs");
//        $breadcrumbs->addItem('');
        // end breadcrumb

        // graphs
//        $opened = $em->getRepository('SalesBundle:Opportunity')->opened($competence);
//        $value = $em->getRepository('SalesBundle:Opportunity')->value($competence);
//        $summaryProposalsDeal = $em->getRepository('SalesBundle:Proposal')->summaryClosed($competence);
//        $opportunitiesPerDay = $em->getRepository('SalesBundle:Opportunity')->perDay($competence);
        // end graphs

//        $lastActivities = $this->get('repository.dashboard')->lastActivities();

        return $this->render('default/index.html.twig', [
            'user' => $user,
        ]);
    }
}
