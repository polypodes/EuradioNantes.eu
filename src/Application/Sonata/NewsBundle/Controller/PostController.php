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

        $query = $this
            ->getDoctrine()
            ->getRepository('ApplicationSonataNewsBundle:Post')
            ->createQueryBuilder('p')
            ->where('p.enabled = 1')
            ->andwhere('p.publicationDateStart <= :now')
            ->setParameter('now', new \Datetime())
            ->addOrderBy('p.publicationDateStart', 'DESC')
        ;

        if (!empty($_GET['collection'])) {
            $query = $query
                ->andWhere('p.collection = :collection')
                ->setParameter('collection', $_GET['collection'])
            ;
        }
        if (!empty($_GET['type'])) {
            $query = $query
                ->andWhere('p.type = :type')
                ->setParameter('type', $_GET['type'])
            ;
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

        if (!$post) {
            throw $this->createNotFoundException('L’actualité est introuvable.');
        }
        // hide content if not published and user not logged
        if (!$post->isPublic() && !$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw $this->createNotFoundException('L’actualité est indisponible.');
        }

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Actualités', $this->get('router')->generate('listeactus'));
        $breadcrumbs->addItem($post->getTitle());

        if ($seoPage = $this->getSeoPage()) {
            $seoPage
                ->addTitle($post->getTitle())
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
            ->createQuery("SELECT p FROM ApplicationSonataNewsBundle:Post p WHERE p.enabled = 1 AND p.publicationDateStart <= :now ORDER BY p.publicationDateStart DESC")
            ->setParameter('now', new \Datetime())
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
