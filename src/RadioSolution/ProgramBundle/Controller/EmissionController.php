<?php

namespace RadioSolution\ProgramBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RadioSolution\ProgramBundle\Entity\Emission;

/**
 * Emission controller.
 *
 * @Route("/")
 */
class EmissionController extends Controller
{
    /**
     * Lists all Emission entities.
     *
     * @Route("/", name="")
     * @Template()
     */

	public function indexAction()
	{
        if (!empty($_GET['emission'])) {
            $this->redirect($_GET['emission']);
        }
        $em = $this->getDoctrine()->getManager();

        $query = $this
            ->getDoctrine()
            ->getRepository('ProgramBundle:Emission')
            ->createQueryBuilder('e')
            //->where('e.name <> "" ')
            ->orderBy('e.name', 'ASC')
        ;

        if (!empty($_GET['theme'])) {
            $query = $query
                ->andWhere('e.theme = :theme')
                ->setParameter('theme', $_GET['theme'])
            ;
        }
        if (!empty($_GET['frequency'])) {
            $query = $query
                ->andWhere('e.frequency = :frequency')
                ->setParameter('frequency', $_GET['frequency'])
            ;
        }
        if (!empty($_GET['archive'])) {
            $query = $query
                ->andWhere('e.archive = :archive')
                ->setParameter('archive', $_GET['archive'])
            ;
        } else{
            $query = $query->andWhere('e.archive = 0');
        }

        $query = $query->getQuery();

        $paginator = $this->get('knp_paginator');

        $entities = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1),
            6
        );

		$themes = $this
            ->getDoctrine()
            ->getRepository('ProgramBundle:EmissionTheme')
            ->findAll()
        ;

        $emissions = $this
            ->getDoctrine()
            ->getRepository('ProgramBundle:Emission')
            ->createQueryBuilder('e')
            ->where('e.archive = 0')
            ->orderBy('e.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        $frequencies = $this
            ->getDoctrine()
            ->getRepository('ProgramBundle:EmissionFrequency')
            ->findAll()
        ;

		return $this->render('ProgramBundle:Emission:index.html.twig', compact('entities', 'emissions', 'frequencies', 'themes'));
	}


    /**
     * Finds and displays a Emission entity.
     *
     * @Route("/{id}/show", name="_show")
     * @Template()
     */
    public function showAction($name)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ProgramBundle:Emission')->findOneBySlug($name);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Emission entity.');
        }

        return $this->render('ProgramBundle:Emission:show.html.twig', array(
        	'entity'	=> $entity
        ));
    }

    public function showRssAction($name)
    {
    	$dateNow =new \DateTime();
    	$domain = $this->get('request')->server->get('HTTP_HOST');
    	$em = $this->getDoctrine()->getManager();

        if (!$emission = $em->getRepository('ProgramBundle:Emission')->findOneBySlug($name)) {
            throw $this->createNotFoundException('Unable to find Emission entity.');
        }

    	$query = $em
            ->createQuery("SELECT p FROM PodcastBundle:Podcast p JOIN p.program pr WHERE p.real_time_start < :dateNow AND pr.emission = :idEmission ORDER BY p.real_time_start DESC")
        	->setMaxResults(10)
        	->setParameter('dateNow', $dateNow)
        	->setParameter('idEmission',$emission->getId())
        ;
    	$podcasts = $query->getResult();
    	return $this->render('ProgramBundle:Emission:show.rss.twig', compact('emission','podcasts','domain'));
    }
}
