<?php
namespace  RadioSolution\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class MenuController extends Controller
{
    //public function MenuAction()
    //{
    //	return $this->render('MenuBundle:Block:render.html.twig',array('idmenu' => 1));
    //}

    public function sitemapAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $menus = $em
            ->getRepository('MenuBundle:Menu')
            ->findAll()
        ;

        return $this->render('MenuBundle:Menu:sitemap.html.twig', compact('menus'));
    }
}
