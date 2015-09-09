<?php

namespace Application\Sonata\NewsBundle\Controller;

use Application\Sonata\NewsBundle\Entity\Post;

use Sonata\NewsBundle\Controller\PostController as BaseController;

class PostController extends BaseController
{
    public function indexAction()
    {
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

    public function getLastPosts($limit = 9)
    {
        $em = $this->getDoctrine()->getEntityManager();
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
