<?php

namespace RadioSolution\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em
            ->createQuery("SELECT p FROM ApplicationSonataNewsBundle:Post p WHERE p.enabled = 1 ORDER BY p.publicationDateStart DESC")
            ->setMaxResults(18)
        ;

        $posts = $query->getResult();

        return $this->render('SiteBundle:Home:index.html.twig', compact('posts'));
    }
}
