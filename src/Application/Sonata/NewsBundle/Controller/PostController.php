<?php

namespace Application\Sonata\NewsBundle\Controller;

use Application\Sonata\NewsBundle\Entity\Post;

use Sonata\NewsBundle\Controller\PostController as BaseController;

class PostController extends BaseController
{
    public function indexAction(){
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

        return $this->render('ApplicationSonataNewsBundle:Post:index.html.twig', compact('news', 'collection'));
    }

    // public function showAction($id){
    //     $actu = $this->getDoctrine()
    //                 ->getRepository('ApplicationSonataNewsBundle:Post')
    //                 ->find($id)
    //             ;
    //     if (!$actu) {
    //         throw $this->createNotFoundException(
    //             'Aucun produit trouvé pour cet id : '.$id
    //         );
    //     }
    //     return $this->render('ApplicationSonataNewsBundle:Post:show.html.twig', compact('actu'));
    // }

}

 ?>
