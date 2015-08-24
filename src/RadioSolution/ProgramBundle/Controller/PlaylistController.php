<?php

namespace RadioSolution\ProgramBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RadioSolution\ProgramBundle\Entity\Playlist;

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
        $playlists = $this->getDoctrine()->getRepository('ProgramBundle:Playlist')->findAllFeatured(20);

        return $this->render('ProgramBundle:Playlist:index.html.twig', compact('playlists'));
    }

    /**
     * Show playlist from its id
     */
    public function showAction(Playlist $playlist) {
        //$playlist = $this->getDoctrine()->getRepository('ProgramBundle:Playlist')->findBySlugPublished($id);
        if (!$playlist || !$playlist->getPublished()) {
            throw $this->createNotFoundException('La playlist est introuvable.');
        }
        return $this->render('ProgramBundle:Playlist:show.html.twig', compact('playlist'));
    }

}
