<?php
/**
 * This file is part of the EuradioNantes.eu package.
 *
 * (c) 2015 Les Polypodes
 * Made in Nantes, France - http://lespolypodes.com
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 *
 * File created by ronan@lespolypodes.com (06/08/2015 - 09:21)
 */

namespace RadioSolution\ProgramBundle\Tests\Controller;

use RadioSolution\ProgramBundle\Service\Tracks\TrackRetriever;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrackRetreiverTest extends WebTestCase
{
    /**
     * @dataProvider dataProvider
     */
    /*
    public function testPrepareItems($terms)
    {
        $this->markTestSkipped();
        $retriever = $this->getRetriever();
        list($artist, $title, $albumName) = $retriever->prepareItems($terms);

        $this->assertNotNull($artist);
        $this->assertNotNull($title);
        // AlbumName may be null
    }
    */

    protected function getRetriever()
    {
        $client = self::createClient();
        $container = $client->getContainer();

        return new TrackRetriever($container,
            $container->getParameter("locale"),
            $container->getParameter("amazon_ws_api_key"),
            $container->getParameter("amazon_ws_api_secret_key"),
            $container->getParameter("amazon_ws_api_associate_tag")
        );
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAlbumSearch($terms)
    {
        $retriever = $this->getRetriever();
        sleep(1); // otherwise Amazon kicks you for overusing its API.
        list($album, $images, $tracks) = $retriever->search($terms);
        $this->assertNotNull($album);
        $this->assertNotNull($images);
        $this->assertNotNull($tracks);
    }

    public function dataProvider()
    {
        return [
            ["THE DODOS - DARKNESS - Individ"],
            ["THE WAR ON DRUGS - LOST IN THE DREAM - LOST IN THE DREAM"],
            ["RATATAT - PRICKS OF BRIGHTNESS - MAGNIFIQUE 2015"],
            ["DUTCH UNCLES (ANG) - UPSILON - O SHUDDER"],
            ["[THE LIMINANAS (FRA) - I'VE GOT TROUBLE IN MIND - I've Got Trouble In Mind 2014"],
            ["ALISON MOSSHART [COVER] - WHAT A WONDERFUL WORLD - Sons Of Anarchy Soundtrack 2011"],
            ["THE GO TEAM (ANG) - WAKING THE JETSTREAM - THE SCENE BETWEEN"],
            ["ACID BABY JESUS (GRE) - VEGETABLE - ACID BABY JESUS \"Selected Recordings\" 2014"],
            ["OWLLE (FRA) - FOG - FRANCE"],
            ["BIKINI MACHINE (FRA) - EVERYBODY'S IN THE KNOW - Bang on Time 2014"],
            ["ALLAH-LAS - NOTHING TO HIDE - WORSHIP THE SUN"],
            ["WITHERED HAND (ECO) - KING OF HOLLYWOOD - New Gods 2013"],
            ["JACK WHITE - TEMPORARY GROUND - LAZARETTO"],
            ["RATATAT - PRICKS OF BRIGHTNESS - MAGNIFIQUE 2015"],
            ["JIMI GOODWIN (ANG) - OH WHISKEY RECALL - Odludek"],
            ["TODD TERJE (NOR) - ALFONSO MUSKEDUNDER - It's Album Time 2014"],
            ["FUNKEN (FRA) - HOLIDAY - MICHEL"],
            ["THE GO TEAM (ANG) - WAKING THE JETSTREAM - THE SCENE BETWEEN"],
            ["THE LIMINANAS (FRA) - LA FILLE DE LA LIGNE 15 - I've Got Trouble In Mind 2014"],
            ["DUTCH UNCLES (ANG) - UPSILON - O SHUDDER"],
            ["CLOSE LOBSTERS (ECO) - WIDE WATERWAYS - Firestation Towers 1986-1989 2015"],
            ["BENJAMIN BOOKER - HAPPY HOMES - BENJAMIN BOOKER"],
            ["BOOGERS (FRA) - I'M THERE - Running in the flame 2014"],
            ["BARBAGALLO (FRA) - LE DERNIER PAYS - Amor de Lonh 2014"],
            ["THE WAR ON DRUGS - LOST IN THE DREAM - LOST IN THE DREAM"],
            ["THE WAR ON DRUGS - LOST IN THE DREAM - "],
            ["THE WAR ON DRUGS - UNDER THE PRESSURE"],
            ["PAUL SMITH & PETER BREWIS (ANG - A TOWN CALLED LETTER - FROZEN BY SIGHT"],

            /*
             * These fail:

            ["COLD MAILMAN (NOR) - SOMETHING YOU DO - Everything Aflutter 2014"],
            ["THE KOOKS (ANG) [COVER] - YOUNG FOLKS - Album inconnu (07/03/2008 15:1"],
            ["JOY WELLBOY (BEL) - COMME SUR DES ROULETTES -"],
            ["CLAUDINE LONGET [COVER] - LET'S SPEND THE NIGHT TOGETHER -"],
            ["RADIO ELVIS (FRA) - LA TRAVERSEE - JUSTE AVANT LA RUEE"],
             */

        ];
    }
}
