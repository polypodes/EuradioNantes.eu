<?php 

namespace Application\Sonata\NewsBundle\Controller;

use Application\Sonata\NewsBundle\Entity\Post;

use Sonata\NewsBundle\Controller\PostController as BaseController;

class PostController extends BaseController
{
    public function indexAction(){
        $news = $this->getDoctrine()
                    ->getRepository('ApplicationSonataNewsBundle:Post')
                    ->listAll()
                ;
        return $this->render('ApplicationSonataNewsBundle:Post:index.html.twig', compact('news'));
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