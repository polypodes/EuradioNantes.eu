<?php

namespace RadioSolution\ProgramBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RadioSolution\ProgramBundle\Entity\Track;

/**
 * Album controller.
 */
class AlbumController extends Controller
{
    /**
     * Lists all Album entities
     */
    public function indexAction()
    {

        //$em = $this->getDoctrine()->getEntityManager();
        //$query = $em->createQuery("SELECT p FROM ProgramBundle:Program p WHERE p.time_stop<:stop AND p.time_start>:start AND p.time_start<p.time_stop  ORDER BY p.time_start ASC")
        //->setParameters(array('start'=>$start,'stop'=>$stop));
        //$entities=$query->getResult();

        $albums = $this->getDoctrine()->getRepository('ProgramBundle:Album')->findAll();
        //var_dump($Albums);

        return $this->render('ProgramBundle:Album:index.html.twig', compact('albums'));
    }

    /**
     * Show album from its id
     */
    public function showAction($id) {
        $album = $this->getDoctrine()->getRepository('ProgramBundle:Album')->find($id);
        if (!$album) {
            throw $this->createNotFoundException('L’album est introuvable.');
        }
        return $this->render('ProgramBundle:Album:show.html.twig', compact('album'));
    }

}
