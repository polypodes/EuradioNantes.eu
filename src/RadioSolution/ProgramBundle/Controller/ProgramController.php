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
        $format = $this->get('request')->get('_format');

        $timestampDay = 60*60*24;
        $timestampWeek = $timestampDay*7;

        $start = new \Datetime('now');
        $start->setTime(0, 0);

        if (isset($_GET['week'])) {
            $start->setISODate($start->format('Y'), $_GET['week'], 1);
            $weekNumber = $_GET['week'];
        } else {
            $weekNumber = date("W");
        }

        // find programs on current week
        $dayNumber = $start->format('w');
        if ($dayNumber === 0) {
            $start->modify('-6 days');
        } elseif ($dayNumber > 1) {
            $start->modify('-' . ($dayNumber - 1) . ' days');
        }

        $stop = clone $start;
        $stop->modify('+7 days');



        $em = $this->getDoctrine()->getEntityManager();
        $query = $em
            ->createQuery("SELECT p FROM ProgramBundle:Program p WHERE p.time_stop < :stop AND p.time_start >= :start AND p.time_start < p.time_stop  ORDER BY p.time_start ASC")
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
                    'entities' => array()
                ),
                'am' => array(
                    'label' => 'Matinée',
                    'entities' => array()
                ),
                'pm' => array(
                    'label' => 'Après-midi',
                    'entities' => array()
                ),
                'soir' => array(
                    'label' => 'Soirée',
                    'entities' => array()
                ),
                'nuit' => array(
                    'label' => 'Nuit',
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
            'weekNumber' => $weekNumber,
            'start' => $start,
            'stop' => $stop
        ));
    }

    public function onairAction()
    {
        $onairs = array();
        $onairs[] = "";


        $file = fopen('http://www.euradionantes.eu/uploads/onair/now_playing.txt', 'r');
        $onairs[] = fgets($file);

        //$onairs[] = "Titre;Artiste;album.jpg";
        //$onairs[] = "Titre - Artiste";

        $tab = array();
        $tab = explode(';', $onairs[1]);

        if(count($tab) == 3){

            //echo $onairs[1];
            $onairs[1] = $tab[0]." - ".$tab[1];
            //echo $onairs[1];

        }

        ///echo $onairs[1];

        return $this->render('ProgramBundle:Program:show_onair.html.twig', array('onairs' => $onairs));


    }



}
