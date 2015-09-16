<?php

namespace RadioSolution\PodcastBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RadioSolution\PodcastBundle\Entity\Podcast;

/**
 * Podcast controller.
 *
 * @Route("/podcast")
 */
class PodcastController extends Controller
{
    /**
     * Lists all Podcast entities.
     *
     * @Route("/", name="podcast")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $dateNow =new \DateTime();

        if (!empty($_GET['emission'])) {
            $query = $em
                ->createQuery("SELECT p FROM PodcastBundle:Podcast p JOIN p.program pr WHERE p.real_time_start < :dateNow AND pr.emission = :emission ORDER BY p.real_time_start DESC")
                ->setParameter('dateNow', $dateNow)
                ->setParameter('emission', $_GET['emission'])
            ;
            //$entities = $query->getResult();

        } else {
            $date = new \DateTime('-7 month');
            $query = $em
                ->createQuery("SELECT p FROM PodcastBundle:Podcast p WHERE p.real_time_start < :dateNow AND p.real_time_start > :date ORDER BY p.real_time_start DESC")
                ->setParameter('date', $date)
                ->setParameter('dateNow', $dateNow)
            ;
            //$entities = $query->getResult();
        }

        $paginator = $this->get('knp_paginator');
        $podcasts = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1),
            10
        );

        $emissions = $this->getDoctrine()->getRepository('ProgramBundle:Emission')->findAll();

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle("Podcasts - Eur@dioNantes");

        return $this->render('PodcastBundle:Podcast:index.html.twig', compact('podcasts', 'emissions'));
    }

    /**
     * Lists all Podcast entities of a given date.
     */
    public function indexDateAction($date)
    {
        $domain = $this->get('request')->server->get('HTTP_HOST');
        $join='';
        $condition='';
        if (isset($_GET['emission']) && $_GET['emission']!="") {
            $join="JOIN p.program pr";
            $post=$_GET['emission'];
            $condition="AND  pr.emission= $post";
        }
        $dateStart=new \DateTime($date);
        $dateStart->setTime(00, 00);
        $dateStop=new \DateTime($date);
        $dateStop->setTime(23, 59);
        $dateNow =new \DateTime();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT p FROM PodcastBundle:Podcast p $join WHERE p.real_time_start<:dateNow AND p.real_time_start>:dateStart AND p.real_time_start<:dateStop $condition ORDER BY p.real_time_start DESC")
        ->setParameters(array('dateStart'=> $dateStart,'dateStop'=> $dateStop))
        ->setParameter('dateNow', $dateNow);
        $entities=$query->getResult();

        $query = $em->createQuery("SELECT e FROM ProgramBundle:Emission e ORDER BY e.name ASC");
        $emission=$query->getResult();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities,
                $this->get('request')->query->get('page', 1),
                10
        );
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle("Podcasts du ".$dateStart->format('d/m/Y')." - Eur@dioNantes");

        return $this->render('PodcastBundle:Podcast:indexDate.html.twig',array('pagination'=> $pagination,'emissions'=>$emission,'date'=>$date,'domain'=>$domain));

    }

    /**
     * Finds and displays a Podcast entity.
     *
     * @Route("/{id}/show", name="podcast_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PodcastBundle:Podcast')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Podcast entity.');
        }

        return array(
            'entity' => $entity,
        );
    }

    public function emissionAction($id, $page = 1, $title = "Émission - Eur@dioNantes")
    {
        $dateNow = new \DateTime();
        $em = $this->getDoctrine()->getManager();

        $query = $em
            ->createQuery("SELECT p FROM PodcastBundle:Podcast p JOIN p.program pr WHERE p.real_time_start < :dateNow AND pr.emission = :idEmission ORDER BY p.real_time_start DESC")
            ->setParameter('idEmission', $id)
            ->setParameter('dateNow', $dateNow)
        ;

        $paginator = $this->get('knp_paginator');
        $podcasts = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', $page),
            6
        );

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle($title);

        return $this->render('PodcastBundle:Podcast:emission.html.twig', compact('podcasts'));

    }

    public function embedAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $podcast = $em->getRepository('PodcastBundle:Podcast')->find($id);

        if (!$podcast) {
            throw $this->createNotFoundException('Unable to find Podcast entity.');
        }

        return $this->render('PodcastBundle:Podcast:embed.html.twig', compact('podcast'));

    }
}
