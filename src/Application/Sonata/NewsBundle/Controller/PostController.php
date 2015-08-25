<?php 

namespace Application\Sonata\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\Sonata\NewsBundle\Entity\Post;

class PostController extends Controller
{
    public function indexAction(){
        $news = $this->getDoctrine()
                    ->getRepository('ApplicationSonataNewsBundle:Post')
                    ->listAll()
                ;
        return $this->render('ApplicationSonataNewsBundle:Post:index.html.twig', compact('news'));
    }

    public function showAction($id){
        $actu = $this->getDoctrine()
                    ->getRepository('ApplicationSonataNewsBundle:Post')
                    ->find($id)
                ;
        if (!$actu) {
            throw $this->createNotFoundException(
                'Aucun produit trouvé pour cet id : '.$id
            );
        }
        return $this->render('ApplicationSonataNewsBundle:Post:show.html.twig', compact('actu'));
    }
}

 ?>