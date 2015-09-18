<?php

namespace RadioSolution\StaticContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use RadioSolution\StaticContentBundle\Entity\StaticContent;

/**
 * StaticContent controller.
 *
 */
class StaticContentController extends Controller
{
    /**
     * Finds and displays a StaticContent entity.
     *
     */
    public function showAction(Request $request, $slug)
    {
        //\$page->setTitle('test');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StaticContentBundle:StaticContent')->findOneBySlug($slug);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StaticContent entity.');
        }

        // breadcrumbs
        $breadcrumbs = $this->get("white_october_breadcrumbs");

        if ($items = $em
            ->createQuery('SELECT i FROM MenuBundle:Item i WHERE i.url = :url ORDER BY i.menu ASC, i.order_item ASC')
            ->setParameter('url', substr($request->getPathInfo(), 1))
            ->getResult()
        ) {
            if ($parentId = $items[0]->getParent()) {
                if ($parent = $em
                    ->getRepository('MenuBundle:Item')
                    ->find($parentId)
                ) {
                    do {
                        $url = $parent->getUrl();
                        $breadcrumbs->prependItem($parent->getName(), $url != '#' ? '/'. $url : null);
                    }
                    while ($parent = $parent->getParent());
                }

            }
        }
        $breadcrumbs->addItem($entity->getName());

        //var_dump($this->container);
        if ($seoPage = $this->container->get('sonata.seo.page')) {
            $seoPage
                ->addTitle($entity->getName())
                ->addMeta('name', 'description', $entity->getIntroduction())
                ->addMeta('property', 'og:title', $entity->getName())
                ->addMeta('property', 'og:type', 'article')
                ->addMeta('property', 'og:url', $this->generateUrl('static_content', array(
                    'slug'  => $entity->getSlug()
                ), true))
                ->addMeta('property', 'og:description', $entity->getIntroduction())
            ;
        }

        return $this->render('StaticContentBundle:StaticContent:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    public function showListAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em
            ->getRepository('StaticContentBundle:CategoryStaticContent')
            ->findOneBySlug($slug)
        ;
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StaticContent entity.');
        }

        // breadcrumbs
        $breadcrumbs = $this->get("white_october_breadcrumbs");

        if ($items = $em
            ->createQuery('SELECT i FROM MenuBundle:Item i WHERE i.url = :url ORDER BY i.menu ASC, i.order_item ASC')
            ->setParameter('url', substr($request->getPathInfo(), 1))
            ->getResult()
        ) {
            if ($parentId = $items[0]->getParent()) {
                if ($parent = $em
                    ->getRepository('MenuBundle:Item')
                    ->find($parentId)
                ) {
                    do {
                        $url = $parent->getUrl();
                        $breadcrumbs->prependItem($parent->getName(), $url != '#' ? '/'. $url : null);
                    }
                    while ($parent = $parent->getParent());
                }

            }
        }
        $breadcrumbs->addItem($entity->getName());

        $categories = $em
            ->getRepository('StaticContentBundle:CategoryStaticContent')
            ->findByParent($entity->getId())
        ;

        $condition = '';
        $i = 0;
        foreach ($categories as $subcategorie){
            if ($i != 0) {
                $condition .= ' OR ';
            }
            $condition .= 'c.categoryStaticContent='.$subcategorie->getId();
            $i ++;
        }

        $entities = $em
            ->createQuery('SELECT c FROM StaticContentBundle:StaticContent c WHERE ('.$condition.') ORDER BY c.order_content DESC, c.date_add ASC')
            ->setMaxResults(20)
            ->getResult()
        ;

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle($entity->getName() . ' - Eur@dioNantes');


        return $this->render('StaticContentBundle:CategoryStaticContent:showList.html.twig', array(
            'entity' => $entity,
            'entities' => $entities,
        ));


    }

    public function showCategorieAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em
            ->getRepository('StaticContentBundle:CategoryStaticContent')
            ->findOneBySlug($slug)
        ;
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StaticContent entity.');
        }

        // breadcrumbs
        $breadcrumbs = $this->get("white_october_breadcrumbs");

        if ($items = $em
            ->createQuery('SELECT i FROM MenuBundle:Item i WHERE i.url = :url ORDER BY i.menu ASC, i.order_item ASC')
            ->setParameter('url', substr($request->getPathInfo(), 1))
            ->getResult()
        ) {
            if ($parentId = $items[0]->getParent()) {
                if ($parent = $em
                    ->getRepository('MenuBundle:Item')
                    ->find($parentId)
                ) {
                    do {
                        $url = $parent->getUrl();
                        $breadcrumbs->prependItem($parent->getName(), $url != '#' ? '/'. $url : null);
                    }
                    while ($parent = $parent->getParent());
                }

            }
        }
        $breadcrumbs->addItem($entity->getName());

        $query = $em
            ->createQuery('SELECT c FROM StaticContentBundle:StaticContent c WHERE c.categoryStaticContent=:idCategory ORDER BY c.order_content DESC, c.date_add ASC')
            ->setParameter('idCategory', $entity->getId())
            //->getResult()
        ;

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query,
                $this->get('request')->query->get('page', 1),
                6
        );

        //$seoPage = $this->container->get('sonata.seo.page');
        //$seoPage->setTitle($entity->getName() . ' - Eur@dioNantes');

        if ($seoPage = $this->get('sonata.seo.page')) {
            $seoPage
                ->setTitle($entity->getName())
                ->addMeta('name', 'description', $entity->getBody())
                ->addMeta('property', 'og:title', $entity->getName())
                ->addMeta('property', 'og:type', 'article')
                ->addMeta('property', 'og:url', $this->generateUrl('static_content_categorie', array(
                    'slug'  => $entity->getSlug()
                ), true))
                ->addMeta('property', 'og:description', $entity->getBody())
            ;
        }

        return $this->render('StaticContentBundle:CategoryStaticContent:showCategorie.html.twig', array(
            'entity' => $entity,
            'entities' => $pagination,
        ));
    }
}
