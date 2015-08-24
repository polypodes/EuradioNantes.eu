<?php

namespace RadioSolution\ProgramBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RadioSolution\ProgramBundle\Entity\Album;

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
        $albums = $this->getDoctrine()->getRepository('ProgramBundle:Album')->findAllFeatured(20);

        return $this->render('ProgramBundle:Album:index.html.twig', compact('albums'));
    }

    /**
     * Show album from its id
     */
    public function showAction(Album $album)
    {
        //$album = $this->getDoctrine()->getRepository('ProgramBundle:Album')->findPublishedBySlug($slug);
        if (!$album || !$album->getPublished()) {
            throw $this->createNotFoundException('L’album est introuvable.');
        }
        return $this->render('ProgramBundle:Album:show.html.twig', compact('album'));
    }

    public function findAction($terms)
    {
        $retriever = $this->container->get('radiosolution.program.trackRetriever');
        list($currentTrackTitle, $terms, $album, $images, $tracks) = $retriever->search($terms);
        die(var_dump($currentTrackTitle, $terms, $album, $images, $tracks));
    }
}
