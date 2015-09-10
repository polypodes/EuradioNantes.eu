<?php

namespace RadioSolution\ProgramBundle\Controller;

use Sonata\PageBundle\Form\Type\TemplateChoiceType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RadioSolution\ProgramBundle\Entity\Emission;

/**
 * Emission controller.
 *
 * @Route("/")
 */
class EcouteController extends Controller
{

    public function indexAction()
    {
        $program = null;
        $onair = null;

        $em = $this->getDoctrine()->getEntityManager();

        $url = $this->container->getParameter('nowPlayingUrl');
        $file = fopen($url, 'r');
        $content = fgets($file);

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

        // broadcast
        //$now = new \DateTime('now');
        //$now->setTime(0, 0);
        //
        //if (!empty($_GET['day'])) {
        //    $start = new \DateTime($_GET['day']);
        //} else {
        //    $start = new \DateTime('now');
        //    $start->setTime(0, 0);
        //}
        //
        //$stop = clone $start;
        //$stop->modify('+1 day');

        $em = $this->getDoctrine()->getEntityManager();

        $query = $em
            ->createQuery("SELECT b FROM ProgramBundle:Broadcast b ORDER BY b.broadcasted DESC")
            ->setMaxResults(20)
            //->setParameters(array('start' => $start, 'stop' => $stop))
        ;

        $broadcasts = $query->getResult();

        /*
    	$day = new \DateTime('now');
    	$start=new \DateTime('now');
    	date_sub($start, date_interval_create_from_date_string('1 days'));
    	$start->setTime('00', '00');
    	$stop=new \DateTime('now');
    	date_add($stop, date_interval_create_from_date_string('1 days'));
    	$stop->setTime('23', '59', '59');


    	$em = $this->getDoctrine()->getEntityManager();

    	$query = $em->createQuery("SELECT p FROM ProgramBundle:Program p WHERE p.time_stop<:stop AND p.time_start>:start ORDER BY p.time_start ASC")
    	->setParameters(array('start'=>$start,'stop'=>$stop));
    	$entities=$query->getResult();

    	$query = $em->createQuery("SELECT p FROM ProgramBundle:Program p WHERE p.time_stop>:now AND p.time_start<:now ORDER BY p.time_start ASC")
    	->setParameters(array('now'=>$day))
    	->setMaxResults(1);
    	$currentProgram=$query->getResult();

    	//echo "".$currentProgram[0]->getName()."<br/>";

    	$nowSlide = 0;

    	if(is_array($currentProgram) && count($currentProgram) > 0){

    		$somethingNow = true;

    	} else {

    		$somethingNow = false;

    		$query = $em->createQuery("SELECT p FROM ProgramBundle:Program p WHERE p.time_start>:now ORDER BY p.time_start ASC")
    		->setParameters(array('now'=>$day))
    		->setMaxResults(1);
    		$currentProgram=$query->getResult();

    	}

    	$nowSlide = array_search($currentProgram[0], $entities);
        */
		return $this->render('ProgramBundle:Program:show_ecoute.html.twig', compact('program', 'onair', 'broadcasts'));
    }

}
