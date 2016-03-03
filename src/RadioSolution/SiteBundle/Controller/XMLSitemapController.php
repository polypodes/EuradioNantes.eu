<?php

namespace RadioSolution\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class XMLSitemapController extends Controller
{

    /**
     * @Route("/sitemap.{_format}", name="xml_sitemap", Requirements={"_format" = "xml"})
     * @Template("AcmeSampleStoreBundle:Sitemaps:sitemap.xml.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $urls = array();
        //$hostname = $this->getRequest()->getHost();
        $hostname = $this->getRequest()->getSchemeAndHttpHost();

        // accueil
        $urls[] = array('loc' => $this->get('router')->generate('home'), 'changefreq' => 'daily', 'priority' => '1.0');

        // écoute en direct
        $urls[] = array('loc' => $this->get('router')->generate('broadcast'), 'changefreq' => 'daily', 'priority' => '5.0');

        // menus et items
        $menus = $em
            ->createQuery("SELECT m FROM MenuBundle:Menu m ORDER BY m.id ASC")
            ->getResult()
        ;
        foreach($menus as $menu) {
            foreach($menu->getItems() as $item) {
                $priority = '0.5';
                $frequency = 'monthly';
                switch ($item->getUrl()) {
                    case 'actualites' :
                        $priority = '0.8';
                        $frequency = 'daily';
                        break;
                    case 'playlists':
                    case 'albums':
                        $priority = '0.7';
                        $frequency = 'weekly';
                        break;
                    case 'labels':
                        $priority = '0.7';
                        break;
                    case 'plan-du-site':
                    case 'article/mentions-legales':
                        $priority = '0.3';
                        break;

                }
                $urls[] = array(
                    'loc' => $item->getUrl(),
                    'changefreq' => $frequency,
                    'priority' => $priority
                );
            }

        }

        // actus et podcasts
        $posts = $em
            ->createQuery("SELECT p FROM ApplicationSonataNewsBundle:Post p WHERE p.enabled = 1 AND p.publicationDateStart <= :now ORDER BY p.position DESC, p.publicationDateStart DESC")
            ->setParameter('now', new \DateTime())
            //->setMaxResults(18)
            ->getResult()
        ;
        foreach ($posts as $post) {
            $urls[] = array(
                'loc' => $this->container->get('sonata.news.blog')->getPermalinkGenerator()->generate($post), //$this->get('router')->generate('home_product_detail', array('productSlug' => $post->getSlug())),
                'priority' => '0.9',
                //'lastmod' => $post->getUpdatedAt()->format('c')
            );
        }

        // les émissions
        $emissions = $em
            ->createQuery("SELECT e FROM ProgramBundle:Emission e WHERE e.published = 1 ORDER BY e.name ASC")
            //->setMaxResults(18)
            ->getResult()
        ;
        foreach ($emissions as $emission) {
            $urls[] = array(
                'loc' => $this->get('router')->generate('emission', array('name' => $emission->getSlug())),
                'priority' => '0.8',
                'lastmod' => $emission->getUpdatedAt()->format('c')
            );
        }

        // les playlists
        $playlists =  $this->getDoctrine()->getRepository('ProgramBundle:Playlist')->queryPublishedOrderedByFeaturedFrom()->getQuery()->getResult();
        //$em
        //    ->createQuery("SELECT p FROM ProgramBundle:Playlist p WHERE p.published = 1 ORDER BY e.name ASC")
        //    //->setMaxResults(18)
        //    ->getResult()
        //;
        foreach ($playlists as $playlist) {
            $urls[] = array(
                'loc' => $this->get('router')->generate('playlist', array('slug' => $playlist->getSlug())),
                'priority' => '0.7',
                'lastmod' => $playlist->getUpdatedAt()->format('c')
            );
        }

        // les albums
        $albums = $this->getDoctrine()->getRepository('ProgramBundle:Album')->queryPublishedOrderedByFeaturedFrom()->getQuery()->getResult();
        foreach ($albums as $album) {
            $urls[] = array(
                'loc' => $this->get('router')->generate('album', array('slug' => $album->getSlug())),
                'priority' => '0.7',
                'lastmod' => $album->getUpdatedAt()->format('c')
            );
        }

        // les labels
        $labels = $this->getDoctrine()->getRepository('ProgramBundle:Label')->queryPublishedOrderedByFeaturedFrom()->getQuery()->getResult();
        foreach ($labels as $label) {
            $urls[] = array(
                'loc' => $this->get('router')->generate('playlist', array('slug' => $label->getSlug())),
                'priority' => '0.7',
                'lastmod' => $label->getUpdatedAt()->format('c')
            );
        }

        return $this->render('SiteBundle:XMLSitemap:sitemap.xml.twig', array(
            'urls' => $urls,
            'hostname' => $hostname
        ));
    }
}
