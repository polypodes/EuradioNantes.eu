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
 * File created by ronan@lespolypodes.com (13/08/2015 - 14:36)
 */

namespace RadioSolution\ProgramBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use RadioSolution\ProgramBundle\Service\Tracks\TrackRetriever;

/**
 * Class NowPlayingCommand
 * @package RadioSolution\ProgramBundle\Command
 */
class NowPlayingCommand extends ContainerAwareCommand
{
    /**
     * @var TrackRetriever
     */
    protected $nowPlaying = null;

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('euradionantes:track')
            ->setDescription('Get and record the current track and its album details')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->nowPlaying = $this->getContainer()->get("radiosolution.program.nowPlaying");
        $terms = $this->nowPlaying->fetchTerms()->getTerms();
        if(isset($terms)) {
            $output->writeln(sprintf("Fetched terms %s being processed through Amazon Product API...", $terms));
            list($currentTrack, $broadcast, $terms, $album, $tracks) = $this->nowPlaying->execute();
            $output->writeln(sprintf('Album %d "%s" processed', $album->getId(), $album->getTitle()));
            $output->writeln(sprintf('new Broadcast %d "%s" added', $broadcast->getId(), $currentTrack->getTitle()));

        } else {
            $output->writeln(sprintf("terms in unreachable or is invalid"));
        }

        return null;
    }
}