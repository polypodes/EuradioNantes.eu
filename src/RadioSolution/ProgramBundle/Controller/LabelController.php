<?php

namespace RadioSolution\ProgramBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
//use RadioSolution\ProgramBundle\Entity\label;
use RadioSolution\ProgramBundle\Entity\Label;


/**
 * Label controller.
 */
class LabelController extends Controller
{
    /**
     * Lists all Label entities
     */
    public function indexAction(Request $request)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Musique');
        $breadcrumbs->addItem('Le label européen du mois');

        //$labels = $this->getDoctrine()->getRepository('ProgramBundle:Label')->findAllFeatured(20);

        $query = $this->getDoctrine()->getRepository('ProgramBundle:Label')->queryPublishedOrderedByFeaturedFrom()->getQuery();

        $paginator  = $this->get('knp_paginator');
        $labels = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('ProgramBundle:Label:index.html.twig', compact('labels'));
    }

    /**
     * Show label from its id
     * $slug   string
     */
    public function showAction(Label $label) {
        //$label = $this->getDoctrine()->getRepository('ProgramBundle:Label')->findPublishedBySlug($slug);
        if (!$label || !$label->getPublished()) {
            throw $this->createNotFoundException('Le label est introuvable.');
        }

        if ($seoPage = $this->get('sonata.seo.page')) {
            $seoPage
                ->setTitle($label->getTitle())
                ->addMeta('name', 'description', $label->getResume())
                ->addMeta('property', 'og:title', $label->getTitle())
                ->addMeta('property', 'og:type', 'article')
                ->addMeta('property', 'og:url', $this->generateUrl('label', array(
                    'slug'  => $label->getSlug()
                ), true))
                ->addMeta('property', 'og:description', $label->getResume())
            ;
        }

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Musique');
        $breadcrumbs->addRouteItem('Le label européen du mois', 'labels');
        $breadcrumbs->addItem($label->getTitle());

        return $this->render('ProgramBundle:Label:show.html.twig', compact('label'));
    }

}
