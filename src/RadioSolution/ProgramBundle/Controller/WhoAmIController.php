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

    /**
     *
     * THIS IS A TEST URL, only for Now-Playing related tests purpose.
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \RadioSolution\ProgramBundle\Exception\InvalidTermsInputException
     * @throws \RadioSolution\ProgramBundle\Exception\InvalidTrackInputException
     */
    public function indexAction()
    {

        $nowPlaying = $this->get("radiosolution.program.nowPlaying");
        $terms = $nowPlaying->fetchTerms()->getTerms();
        if(isset($terms)) {
            $output = sprintf("Fetched terms %s being processed through Amazon Product API...", $terms);
            list($currentTrack, $broadcast, $terms, $album, $tracks) = $nowPlaying->execute();
            $output = sprintf('Album %d "%s" processed', $album->getId(), $album->getTitle());
            $output .= sprintf("\nnew Broadcast %d '%s' added",
                $broadcast->getId(),
                $currentTrack->getTitle()
            );

        } else {
            $output = sprintf("terms in unreachable or is invalid");
        }

        return $this->render('ProgramBundle::empty_layout.html.twig', array(
            'content' => $output
        ));
    }
}
