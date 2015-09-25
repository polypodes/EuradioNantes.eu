<?php

namespace RadioSolution\ProgramBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RadioSolution\ProgramBundle\Entity\Emission;
use Ps\PdfBundle\Annotation\Pdf;

/**
 * Emission controller.
 *
 * @Route("/")
 */
class ProgramController extends Controller
{
    /**
     * Lists all Emission entities.
     */
    public function indexAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Les émissions');
        $breadcrumbs->addItem('Grille des programmes');

        $format = $this->get('request')->get('_format');

        $timestampDay = 60*60*24;
        $timestampWeek = $timestampDay*7;


        if (!empty($_GET['date'])) {
            $start = new \Datetime($_GET['date']);
            if ($start->format('W') != 1) {
                $start->setISODate($start->format('Y'), $start->format('W'), 1);
            }
        } elseif (!empty($_GET['week'])) {
            $start = new \Datetime();
            $start->setISODate($start->format('Y'), $_GET['week'], 1);
        }
        if (empty($start)) {
            $start = new \Datetime('now');
            $start->setTime(0, 0);
        }
        $weekNumber = $start->format('W');

        // find programs on current week
        $dayNumber = $start->format('w');
        if ($dayNumber === 0) {
            $start->modify('-6 days');
        } elseif ($dayNumber > 1) {
            $start->modify('-' . ($dayNumber - 1) . ' days');
        }

        $stop = clone $start;
        $stop->modify('+7 days');

        $em = $this->getDoctrine()->getManager();
        $query = $em
            ->createQuery("SELECT p FROM ProgramBundle:Program p WHERE p.time_stop < :stop AND p.time_start >= :start AND p.time_start < p.time_stop  ORDER BY p.time_start ASC, p.time_stop DESC")
            ->setParameters(array('start' => $start, 'stop' => $stop))
        ;
        $results = $query->getResult();

        $entities = array();

        for ($i = 0; $i < 7; $i++) {
            $day = clone $start;
            $day->modify("+$i days");
            $entities[] = array(
                'date' => $day,
                'tot' => array(
                    'label' => 'Tôt',
                    'desc' => 'Avant 7h00',
                    'entities' => array()
                ),
                'am' => array(
                    'label' => 'Matinée',
                    'desc' => '7h00 - 12h00',
                    'entities' => array()
                ),
                'pm' => array(
                    'label' => 'Après-midi',
                    'desc' => '12h00 - 18h00',
                    'entities' => array()
                ),
                'soir' => array(
                    'label' => 'Soirée',
                    'desc' => '18h00 - 21h00',
                    'entities' => array()
                ),
                'nuit' => array(
                    'label' => 'Nuit',
                    'desc' => 'Après 21h00',
                    'entities' => array()
                ),
            );
        }

        foreach ($results as $result) {
            $weekDay = $result->getTimeStart()->format('N') - 1;
            if ($result->getTimeStart()->format('H:i:s') < '07:00:00') {
                $entities[$weekDay]['tot']['entities'][] = $result;
            } elseif ($result->getTimeStart()->format('H:i:s') < '12:00:00') {
                $entities[$weekDay]['am']['entities'][] = $result;
            } elseif ($result->getTimeStart()->format('H:i:s') < '18:00:00') {
                $entities[$weekDay]['pm']['entities'][] = $result;
            } elseif ($result->getTimeStart()->format('H:i:s') < '21:00:00') {
                $entities[$weekDay]['soir']['entities'][] = $result;
            } else {
                $entities[$weekDay]['nuit']['entities'][] = $result;
            }
        }

        return  $this->render(sprintf('ProgramBundle:Program:index.%s.twig', $format), array(
            'entities' => $entities,
            'count' => count($results),
            'weekNumber' => $weekNumber,
            'start' => $start,
            'stop' => $stop
        ));
    }

    public function onairAction()
    {
        $em = $this->getDoctrine()->getManager();

        $url = $this->container->getParameter('nowPlayingUrl');

        try {
            $file = fopen($url, 'r');
            $content = fgets($file);

            if ($content == "EuradioNantes - La diversite europeenne au creux de l'oreille") {
                $program = $em
                    ->createQuery("SELECT p FROM ProgramBundle:Program p WHERE p.time_stop > :now AND p.time_start >= :now AND p.time_start < p.time_stop  ORDER BY p.time_start ASC, p.time_stop DESC")
                    ->setParameters(array('now' => new \Datetime()))
                    ->setMaxResults(1)
                    ->getOneOrNullResult()
                ;
                //var_dump($result);
                if ($program) {
                    $content = $program->getEmission()->getName();
                }

            } else {
                $broadcast = $em
                    ->createQuery("SELECT b FROM ProgramBundle:Broadcast b WHERE b.broadcasted > :mindate ORDER BY b.broadcasted DESC")
                    ->setParameters(array('mindate' => new \Datetime('-1 hour')))
                    ->setMaxResults(1)
                    ->getOneOrNullResult()
                ;
                //var_dump($result);
                if ($broadcast) {
                    if ($track = $broadcast->getTrack()) {
                        $content = $track->getArtist() . ' - ' . $track->getTitle() . ' - ' . $track->getAlbum()->getTitle();
                    } else {
                        $content = $broadcast->getTerms();
                    }
                }
            }
        } catch (\Exception $e) {
            $content = 'Information indisponible';
        }

        $onair = $content;

        ///echo $onairs[1];

        return $this->render('ProgramBundle:Program:show_onair.html.twig', compact('onair'));


    }



}
