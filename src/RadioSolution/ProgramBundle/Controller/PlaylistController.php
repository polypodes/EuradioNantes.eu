<?php

namespace RadioSolution\ProgramBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RadioSolution\ProgramBundle\Entity\Album;

/**
 * Label controller.
 */
class PlaylistController extends Controller
{
    /**
     * Lists all Playlist entities
     *
     */
    public function indexAction()
    {
        $playlists = $this->getDoctrine()->getRepository('ProgramBundle:Playlist')->findAll();

        return $this->render('ProgramBundle:Playlist:index.html.twig', compact('playlists'));
    }

    /**
     * Show playlist from its id
     */
    public function showAction($id) {
        $playlist = $this->getDoctrine()->getRepository('ProgramBundle:Playlist')->find($id);
        if (!$playlist) {
            throw $this->createNotFoundException('La playlist est introuvable.');
        }
        return $this->render('ProgramBundle:Playlist:show.html.twig', compact('playlist'));
    }

}
