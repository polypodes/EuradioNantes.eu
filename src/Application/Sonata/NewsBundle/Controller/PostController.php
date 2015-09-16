<?php

namespace Application\Sonata\NewsBundle\Controller;

use Application\Sonata\NewsBundle\Entity\Post;

use Sonata\NewsBundle\Controller\PostController as BaseController;

class PostController extends BaseController
{
    public function indexAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Actualités');

        $repo = $query = $this
            ->getDoctrine()
            ->getRepository('ApplicationSonataNewsBundle:Post')
        ;
        if (!empty($_GET['collection'])) {
            $query = $repo->getPostsByCollection($_GET['collection']);
        } else {
            $query = $repo->getPostsByType('actualite');
        }

        $paginator = $this->get('knp_paginator');
        $news = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1),
            6
        );

        $collection = $this
            ->getDoctrine()
            ->getRepository('ApplicationSonataClassificationBundle:Collection')
            ->findAllByContext('actualite')
        ;

        return $this->render('SonataNewsBundle:Post:index.html.twig', compact('news', 'collection'));
    }

    public function viewAction($permalink)
    {
        $post = $this->getPostManager()->findOneByPermalink($permalink, $this->container->get('sonata.news.blog'));

        if (!$post || !$post->isPublic()) {
            throw $this->createNotFoundException('L’actualité est introuvable.');
        }

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Actualités', $this->get('router')->generate('listeactus'));
        $breadcrumbs->addItem($post->getTitle());

        if ($seoPage = $this->getSeoPage()) {
            $seoPage
                ->setTitle($post->getTitle())
                ->addMeta('name', 'description', $post->getAbstract())
                ->addMeta('property', 'og:title', $post->getTitle())
                ->addMeta('property', 'og:type', 'blog')
                ->addMeta('property', 'og:url', $this->generateUrl('sonata_news_view', array(
                    'permalink'  => $this->getBlog()->getPermalinkGenerator()->generate($post, true)
                ), true))
                ->addMeta('property', 'og:description', $post->getAbstract())
            ;
        }

        return $this->render('SonataNewsBundle:Post:view.html.twig', array(
            'post' => $post,
            'form' => false,
            'blog' => $this->get('sonata.news.blog')
        ));
    }

    public function getLastPosts($limit = 9)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em
            ->createQuery("SELECT p FROM ApplicationSonataNewsBundle:Post p WHERE p.enabled = 1 ORDER BY p.publicationDateStart DESC")
            ->setMaxResults($limit)
        ;

        $entities = $query->getResult();

        return $entities;
    }

    public function getAsidePostsAction()
    {
        $posts = $this->getLastPosts(9);

        return $this->render('SonataNewsBundle:Post:aside.html.twig', compact('posts'));
    }

}

 ?>
