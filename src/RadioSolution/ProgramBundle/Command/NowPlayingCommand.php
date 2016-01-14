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
use RadioSolution\ProgramBundle\Service\Tracks\NowPlaying;

/**
 * Class NowPlayingCommand
 * @package RadioSolution\ProgramBundle\Command
 */
class NowPlayingCommand extends ContainerAwareCommand
{
    /**
     * @var NowPlaying
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
        $now = new \DateTime();
        $now = $now->format('d/m/Y H:i:s');

        if (isset($terms)) {
            try {
                list($currentTrack, $broadcast, $terms, $album, $tracks) = $this->nowPlaying->execute();
            } catch (\Exception $e) {
                $output->writeln(sprintf('%s -- ERROR with terms \"%s\" : %s', $now, $terms, $e->getMessage()));
                return null;
            }
            //$output->writeln(sprintf("%s -- SUCCESS processing terms '%s' => Album #%d '%s' => Broadcast #%d => Track #%s '%s'",
            //    $now,
            //    $terms,
            //    $album->getId(),
            //    $album->getTitle(),
            //    $broadcast->getId(),
            //    $currentTrack->getId(),
            //    $currentTrack->getTitle()
            //));

        } else {
            $output->writeln(sprintf("%s -- SKIPPED terms \"%s\" are invalid => Not processed", $now, $terms));
        }

        return null;
    }
}
