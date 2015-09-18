<?php

namespace RadioSolution\ProgramBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
    public function indexAction(Request $request)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Musique');
        $breadcrumbs->addItem('L’album de la semaine');

        //$albums = $this->getDoctrine()->getRepository('ProgramBundle:Album')->findAllFeatured(20);

        $query = $this->getDoctrine()->getRepository('ProgramBundle:Album')->queryPublishedOrderedByFeaturedFrom()->getQuery();

        $paginator  = $this->get('knp_paginator');
        $albums = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

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

        if ($seoPage = $this->get('sonata.seo.page')) {
            $seoPage
                ->addTitle(sprintf('%s - %s', $album->getArtist(), $album->getTitle()))
                ->addMeta('name', 'description', $album->getResume())
                ->addMeta('property', 'og:title', sprintf('%s - %s', $album->getArtist(), $album->getTitle()))
                ->addMeta('property', 'og:type', 'article')
                ->addMeta('property', 'og:url', $this->generateUrl('album', array(
                    'slug'  => $album->getSlug()
                ), true))
                ->addMeta('property', 'og:description', $album->getResume())
            ;
        }

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Musique');
        $breadcrumbs->addRouteItem('L’album de la semaine', 'albums');
        $breadcrumbs->addItem($album->getArtist() . ' - ' . $album->getTitle());

        return $this->render('ProgramBundle:Album:show.html.twig', compact('album'));
    }

    public function findAction($terms)
    {
        $retriever = $this->container->get('radiosolution.program.trackRetriever');
        list($currentTrackTitle, $terms, $album, $images, $tracks) = $retriever->search($terms);
        die(var_dump($currentTrackTitle, $terms, $album, $images, $tracks));
    }
}
