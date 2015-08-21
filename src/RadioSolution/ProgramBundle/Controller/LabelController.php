<?php

namespace RadioSolution\ProgramBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
//use RadioSolution\ProgramBundle\Entity\Album;
use RadioSolution\ProgramBundle\Entity\Label;

/**
 * Label controller.
 */
class LabelController extends Controller
{
    /**
     * Lists all Label entities
     */
    public function indexAction()
    {
        $labels = $this->getDoctrine()->getRepository('ProgramBundle:Label')->findAllFeatured(20);

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
        return $this->render('ProgramBundle:Label:show.html.twig', compact('label'));
    }

}
