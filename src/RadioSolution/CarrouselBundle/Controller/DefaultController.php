<?php

namespace RadioSolution\CarrouselBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{

    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();

    	$query = $em->createQuery("SELECT s FROM CarrouselBundle:Slider s");
    	$carrousel=$query->getResult();

    	/*$em = $this->getDoctrine()->getManager();
    	$carrousel = $em->getRepository('CarrouselBundle:Slider');*/
        return $this->render('CarrouselBundle:Default:index.html.twig', array(
        		'carrousels' => $carrousel
        		));
    }
}
