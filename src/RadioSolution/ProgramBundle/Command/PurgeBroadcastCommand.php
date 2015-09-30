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

/**
 * Class NowPlayingCommand
 * @package RadioSolution\ProgramBundle\Command
 */
class PurgeBroadcastCommand extends ContainerAwareCommand
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
            ->setName('euradionantes:broadcast:purge')
            ->setDescription('Purge old broadcasts entries aged of more than 7 days')
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
        $em = $this
            ->getContainer()
            ->get('doctrine.orm.entity_manager')
        ;


        $now = new \Datetime();

        $date = clone($now);
        // set maximum date to 7 days ago
        $date->modify('-7 days');

        $now = $now->format('d/m/Y H:i:s');

        //var_dump($date);

        $output->writeln(sprintf("%s -- Purging old broadcasts entries aged of more than 7 days", $now));

        $query = $em
            ->createQuery("SELECT b FROM ProgramBundle:Broadcast b WHERE b.broadcasted < :date ORDER BY b.broadcasted ASC")
            ->setParameters(array('date' => $date))
            //->getQuery()
        ;

        $entities = $query->getResult();

        //var_dump($entities);

        if (!empty($entities)) {
            foreach ($entities as $entity){
                $em->remove($entity);
            }
            $em->flush();

            $output->writeln(sprintf("%s -- %d entries deleted", $now, count($entities)));
        } else {
            $output->writeln(sprintf("%s -- Nothing to purge", $now));
        }

        return null;
    }
}
