<?php

namespace RadioSolution\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    public function indexAction(Request $request)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Recherche');
        if ($request->query->get('search')) {
            $breadcrumbs->addItem(sprintf('Résultats de la recherche « %s »', $request->query->get('search')));
        }

        return $this->render('SearchBundle:Default:index.html.twig');
    }
}
