<?php

namespace RadioSolution\ProgramBundle\Controller;

use RadioSolution\ProgramBundle\Entity\Album;
use RadioSolution\ProgramBundle\Entity\Track;
use RadioSolution\ProgramBundle\Exception\InvalidAlbumInputException;
use RadioSolution\ProgramBundle\Service\Tracks\TrackRetriever;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Emission controller.
 *
 * @Route("/")
 */
class WhoAmIController extends Controller
{ 

    public function indexAction()
    {
        $retriever = new TrackRetriever(
            $this->container,
            $this->getParameter("locale"),
            $this->getParameter("amazon_ws_api_key"),
            $this->getParameter("amazon_ws_api_secret_key"),
            $this->getParameter("amazon_ws_api_associate_tag"));

        $terms = "JIMI GOODWIN (ANG) - OH WHISKEY RECALL - Odludek 2013";
        $terms = "THE RINGO JETS (TUR) - STONE COLD GROUND -";
        $terms = "HOT CHIP (ANG) - WHITE WINE & FRIED CHICKEN - ";
        $terms = "SHARON VON ETTEN - OUR LOVE - ARE WE THERE";
        $terms = "WASHED OUT [COVER] - WICKED GAME";
        $terms = "GIL HOCKMAN - ON MY OWN - DOLOROUS";
        $terms = "FLAMING LIPS - CAN'T GET YOU OUT OF MY HEAD (COVER) - Album inconnu (19/12/2007 15:5";
        $terms = "LILY STORM - THE PEONY - 0610";
        $terms = "HUSBANDS (FRA) - SKIP TO THE LEFT - HUSBANDS";
        $terms = "VESUVIO SOLO - PRESENCE -";
        $terms = "WAND - MELTED ROPE - Golem";
        $terms = "SR CHINARRO (ESP) - MANANA TARDE Y NOCHE -";
        $terms = "ALAMO RACE TRACK (PB) - CIRCLING OVER THE COLD BONES - HAWKS";
        $terms = "FINNMARK (ANG) - LOSING MY STYLE -";
        $terms = "JOSH ROUSE - VALENCIA - 0310";

        // OK :

        $terms = "ACID BABY JESUS (GRE) - VEGETABLE - ACID BABY JESUS \"Selected Recordings\" 2014";
        $terms = "BIKINI MACHINE (FRA) - EVERYBODY'S IN THE KNOW - Bang on Time 2014";
        $terms = "ALLAH-LAS - NOTHING TO HIDE - WORSHIP THE SUN";
        $terms = "WITHERED HAND (ECO) - KING OF HOLLYWOOD - New Gods 2013";
        $terms = "JACK WHITE - TEMPORARY GROUND - LAZARETTO";
        $terms = "RATATAT - PRICKS OF BRIGHTNESS - MAGNIFIQUE 2015";
        $terms = "RADIO ELVIS (FRA) - LA TRAVERSEE - JUSTE AVANT LA RUEE";
        $terms = "JIMI GOODWIN (ANG) - OH WHISKEY RECALL - Odludek";
        $terms = "COLD MAILMAN (NOR) - SOMETHING YOU DO - Everything Aflutter 2014";
        $terms = "TODD TERJE (NOR) - ALFONSO MUSKEDUNDER - It's Album Time 2014";
        $terms = "FUNKEN (FRA) - HOLIDAY - MICHEL";
        $terms = "ALISON MOSSHART [COVER] - WHAT A WONDERFUL WORLD - Sons Of Anarchy Soundtrack 2011";
        $terms = "THE KOOKS (ANG) [COVER] - YOUNG FOLKS - Album inconnu (07/03/2008 15:1";
        $terms = "THE GO TEAM (ANG) - WAKING THE JETSTREAM - THE SCENE BETWEEN";
        $terms = "THE LIMINANAS (FRA) - LA FILLE DE LA LIGNE 15 - I've Got Trouble In Mind 2014";
        $terms = "DUTCH UNCLES (ANG) - UPSILON - O SHUDDER";
        $terms = "CLOSE LOBSTERS (ECO) - WIDE WATERWAYS - Firestation Towers 1986-1989 2015";
        $terms = "BENJAMIN BOOKER - HAPPY HOMES - BENJAMIN BOOKER";
        $terms = "JOY WELLBOY (BEL) - COMME SUR DES ROULETTES -";
        $terms = "CLAUDINE LONGET [COVER] - LET'S SPEND THE NIGHT TOGETHER -";
        $terms = [
            "BOOGERS (FRA) - I'M THERE - Running in the flame 2014",
            "THE WAR ON DRUGS - LOST IN THE DREAM - LOST IN THE DREAM",
            "ALISON MOSSHART [COVER] - WHAT A WONDERFUL WORLD - Sons Of Anarchy Soundtrack 2011",
            "THE LIMINANAS (FRA) - I'VE GOT TROUBLE IN MIND - I've Got Trouble In Mind 2014",
            "BARBAGALLO (FRA) - LE DERNIER PAYS - Amor de Lonh 2014",
            "THE WAR ON DRUGS - LOST IN THE DREAM - LOST IN THE DREAM",
            "RADIO ELVIS (FRA) - LA TRAVERSEE - JUSTE AVANT LA RUEE",
            "OQUESTRADA (POR) - PAREINA MADRUGADA - ATLANTIC BEAT",
            "OWLLE (FRA) - FOG - FRANCE",
            "THE DODOS - DARKNESS - Individ",
            "COLD MAILMAN (NOR) - SOMETHING YOU DO - Everything Aflutter 2014",
            "THE WAR ON DRUGS - UNDER THE PRESSURE",
            "THE WAR ON DRUGS - LOST IN THE DREAM",
            "JOY WELLBOY (BEL) - COMME SUR DES ROULETTES -",
            "PAUL SMITH & PETER BREWIS (ANG - A TOWN CALLED LETTER - FROZEN BY SIGHT",
            "BARBAGALLO (FRA) - LA SOIF - Amor de Lonh 2014",
            "MARTIN CARR (ECO) - I DON'T THINK I'LL MAKE IT - The Breaks 2014",
            "PUBLIC SERVICE BROADCASTING (ANG) - GO - THE RACE FOR SPACE",
            "WAMPIRE - LIFE OF LUXURY - BAZAAR",
            "GIORGIO TUMA FEAT LAETITIA SADIER - THROUGH YOUR HANDS LOVE CAN SHINE - Giorgio Tuma with Laetitia Sadier 2015",
            "POND - MEDECINE HAT - MAN IT FEELS LIKE SPACE AGAIN",
            "BECK - BLUE MOON - MORNING PHASE",
            "JUANA MOLINA - ERAS - WED 21",
            "RATATAT - PRICKS OF BRIGHTNESS - MAGNIFIQUE 2015",
            ];
        $terms = array_pop($terms);

        $albumModel = new Album();
        $trackList = array();
        list($terms, $album, $images, $tracks) = $retriever->search($terms);
        $logger = $this->get('logger');
        try {
            $logger->info(sprintf('Trying to SAVE %s ALBUM INFOS using the TrackRetriever', $terms));
            $albumModel->fromXml($album);
            $albumModel->setImagesfromXml($images);
            $em = $this->getDoctrine()->getManager();
            $em->persist($albumModel);
            $em->flush();
        } catch(InvalidAlbumInputException $e) {
            $logger->error(sprintf('An error occurred : %s', $e));
        }
        if($albumModel->getId()) {
            foreach ($tracks as $position=>$title) {
                if("titre inconnu" == trim(strtolower($title))) {
                    $logger->info(
                        sprintf('INGORING %s - %s TRACK INFOS while processsing %s (album #%s) track list',
                            $terms, $title, $albumModel->getId()));
                    continue;
                }
                try {
                    $logger->info(sprintf('Trying to SAVE %s - %s TRACK INFOS using the TrackRetriever', $terms, $title));
                    $trackModel = new Track();
                    $trackModel->fromAlbum($albumModel, $title, $position+1); // position is an index's an index
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($trackModel);
                    $em->flush();
                    $trackList[] = $trackModel;
                } catch (InvalidAlbumInputException $e) {
                    $logger->error(sprintf('An error occurred : %s', $e));
                }
            }
        }
        $serializer = $this->container->get('serializer');
        $content = $serializer->serialize(array($albumModel, $trackList), 'json');


        return $this->render('ProgramBundle::empty_layout.html.twig', array(
            'content' => $content
        ));
    }
}
