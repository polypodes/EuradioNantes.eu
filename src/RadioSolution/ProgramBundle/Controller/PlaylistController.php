<?php

namespace RadioSolution\ProgramBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
    public function indexAction(Request $request)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Musique');
        $breadcrumbs->addItem('La playlist');

        //$playlists = $this->getDoctrine()->getRepository('ProgramBundle:Playlist')->findAllFeatured(20);

        $query = $this->getDoctrine()->getRepository('ProgramBundle:Playlist')->queryPublishedOrderedByFeaturedFrom()->getQuery();

        $paginator  = $this->get('knp_paginator');
        $playlists = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

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

        if ($seoPage = $this->get('sonata.seo.page')) {
            $seoPage
                ->addTitle($playlist->getTitle())
                ->addMeta('name', 'description', $playlist->getResume())
                ->addMeta('property', 'og:title', $playlist->getTitle())
                ->addMeta('property', 'og:type', 'article')
                ->addMeta('property', 'og:url', $this->generateUrl('playlist', array(
                    'slug'  => $playlist->getSlug()
                ), true))
                ->addMeta('property', 'og:description', $playlist->getResume())
            ;
        }

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Musique');
        $breadcrumbs->addRouteItem('La playlist', 'playlists');
        $breadcrumbs->addItem($playlist->getTitle());

        return $this->render('ProgramBundle:Playlist:show.html.twig', compact('playlist'));
    }

}
