<?php

namespace RadioSolution\ProgramBundle\Controller;

use Sonata\PageBundle\Form\Type\TemplateChoiceType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RadioSolution\ProgramBundle\Entity\Emission;

/**
 * Broadcast controller.
 *
 * @Route("/")
 */
class BroadcastController extends Controller
{

    public function indexAction()
    {
        $now = new \DateTime('now');
        $now->setTime(0, 0);

        if (!empty($_GET['day'])) {
            $start = new \DateTime($_GET['day']);
        } else {
            $start = new \DateTime('now');
            $start->setTime(0, 0);
        }

        $stop = clone $start;
        $stop->modify('+1 day');

        $em = $this->getDoctrine()->getEntityManager();

        $query = $em
            ->createQuery("SELECT b FROM ProgramBundle:Broadcast b WHERE b.broadcasted >= :start AND b.broadcasted < :stop ORDER BY b.broadcasted DESC")
            ->setParameters(array('start' => $start, 'stop' => $stop))
        ;

        $entities = $query->getResult();

        return $this->render('ProgramBundle:Program:broadcast.html.twig', compact('entities', 'now', 'start'));
    }

}
