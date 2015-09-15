<?php

namespace RadioSolution\ProgramBundle\Controller;

use Sonata\PageBundle\Form\Type\TemplateChoiceType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RadioSolution\ProgramBundle\Entity\Emission;
use Symfony\Component\HttpFoundation\Request;

/**
 * Emission controller.
 *
 * @Route("/")
 */
class EcouteController extends Controller
{

    public function indexAction(Request $request)
    {
        $program = null;
        $onair = null;

        $em = $this->getDoctrine()->getEntityManager();

        $program = $em
            ->createQuery("SELECT p FROM ProgramBundle:Program p WHERE p.time_stop > :now AND p.time_start >= :now AND p.time_start < p.time_stop  ORDER BY p.time_start ASC, p.time_stop DESC")
            ->setParameters(array('now' => new \Datetime()))
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;

        $onair = $em
            ->createQuery("SELECT b FROM ProgramBundle:Broadcast b WHERE b.broadcasted > :mindate ORDER BY b.broadcasted DESC")
            ->setParameters(array('mindate' => new \Datetime('-1 hour')))
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;

        $broadcasts = $em
            ->createQuery("SELECT b FROM ProgramBundle:Broadcast b ORDER BY b.broadcasted DESC")
            ->setMaxResults(20)
            //->setParameters(array('start' => $start, 'stop' => $stop))
            ->getResult()
        ;

		return $this->render('ProgramBundle:Program:show_ecoute.html.twig', compact('program', 'onair', 'broadcasts'));
    }

}
