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
 * File created by ronan@lespolypodes.com (13/08/2015 - 15:41)
 */

namespace RadioSolution\ProgramBundle\Service\Tracks;

use Monolog\Logger;
use RadioSolution\ProgramBundle\Entity\Broadcast;
use RadioSolution\ProgramBundle\Exception\InvalidTermsInputException;
use RadioSolution\ProgramBundle\Exception\InvalidTrackInputException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use RadioSolution\ProgramBundle\Entity\Track;
use RadioSolution\ProgramBundle\Entity\Album as AlbumModel;
use RadioSolution\ProgramBundle\Exception\InvalidAlbumInputException;

/**
 * Class NowPlaying.
 */
class NowPlaying implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface|null
     */
    protected $container = null;

    /**
     * @var string 'Now Playing' FTP TXT file
     *
     * @example http://www.euradionantes.eu/uploads/onair/now_playing.txt
     */
    protected $nowPlayingUrl = null;

    /**
     * @var Logger
     */
    protected $logger = null;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager = null;

    /**
     * @var TrackRetriever
     */
    protected $retriever = null;

    /**
     * @var string
     */
    protected $terms = null;

    /**
     *
     */
    const ALREADY_EXISTS = 0;
    /**
     *
     */
    const NEWLY_SAVED = 1;
    /**
     *
     */
    const SOMETHING_TERRIBLE_HAPPENED = -1;

    /**
     * @param ContainerInterface $container
     * @param string             $nowPlayingUrl
     */
    public function __construct(ContainerInterface $container, $nowPlayingUrl)
    {
        $this->setContainer($container);
        $this->nowPlayingUrl = $nowPlayingUrl;
    }

    /**
     *
     */
    protected function setUp()
    {
        $this->retriever = $this->container->get('radiosolution.program.trackRetriever');
        $this->logger = $this->container->get('logger');
        $this->entityManager = $this->container->get('doctrine')->getManager();
    }

    /**
     * @param ContainerInterface|NULL $container
     *
     * @return NowPlaying
     */
    public function setContainer(ContainerInterface $container = null)
    {
        if (empty($container)) {
            throw new \InvalidArgumentException(sprintf("container argument passed to %s cannot be null", __METHOD__));
        }

        $this->container = $container;
    }

    /**
     * @return string
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * @return NowPlaying
     */
    public function fetchTerms($terms = null)
    {
        if ($terms) {
            $this->terms = $terms;
        } else {
            $this->terms = trim(file_get_contents($this->nowPlayingUrl));
        }
        $unProcessable = array(
            "EuradioNantes - La diversite europeenne au creux de l'oreille"
        );

        if (in_array($this->terms, $unProcessable)) {
            $this->terms = null;
        }

        return $this;
    }

    /**
     * @return array [$currentTrack, $broadcast, $this->terms, $albumModel, $trackList]
     * @throws InvalidAlbumInputException
     * @throws InvalidTermsInputException
     * @throws InvalidTrackInputException
     */
    public function execute(string $search = null)
    {
        if (!empty($search)) {
            $this->terms = $search;
        }
        if (!isset($this->retriever)) {
            $this->setUp();
        }
        if (!isset($this->terms)) {
            $this->fetchTerms();
        }

        /** @var Broadcast $broadcast */
        $broadcast = null;
        /** @var Track $currentTrack */
        $currentTrack = null;
        /** @var AlbumModel $albumModel */
        $albumModel = null;
        /** @var array $trackList */
        $trackList = null;
        $code = false;

        if (isset($this->terms)) {
            list($currentTrackTitle, $terms, $album, $images, $tracks) = $this->retriever->search($this->terms);
            try {
                list($code, $albumModel) = $this->saveAlbum($album, $images, $terms);
            } catch (\Exception $e) {
                $this->saveBroadCastBeforeThrowingException($terms, $e);
            }
            switch($code) {
                case self::NEWLY_SAVED:
                    if ($tracks) {
                        $trackList = $this->saveTrackList($albumModel, $terms, $tracks);
                    } else {
                        $trackList = $albumModel->getTracks();
                    }
                    break;
                case self::ALREADY_EXISTS:
                    $trackList = $albumModel->getTracks();
                    break;
                default:
                    $this->saveBroadCastBeforeThrowingException($terms,
                        new InvalidTermsInputException(sprintf("album couldn't be saved in %s", __METHOD__)));
            }

            if(empty($trackList)) {
                $this->saveBroadCastBeforeThrowingException($terms,
                new InvalidTrackInputException(sprintf('trackList not found in %s', __METHOD__)));
            }
            elseif(empty($currentTrackTitle)) {
                $this->saveBroadCastBeforeThrowingException($terms,
                new InvalidTrackInputException(sprintf('currentTrackTitle is empty in %s', __METHOD__)));
            }

            $currentTrack = $this->getTrackByTitle($trackList, $currentTrackTitle);

            if (empty($currentTrack)) {
                $this->saveBroadCastBeforeThrowingException($terms,
                    new InvalidTrackInputException(sprintf(
                        "No '%s' track found in '%s' found album, using given '%s' terms in %s",
                        $currentTrackTitle, $albumModel->getTitle(), $terms, __METHOD__)));
            }

            list($code, $broadcast) = $this->saveBroadcast($terms, $currentTrack);

            if(empty($broadcast) || $code == self::SOMETHING_TERRIBLE_HAPPENED) {
                $this->saveBroadCastBeforeThrowingException($terms,
                 new InvalidTrackInputException(sprintf(
                    "broadcast couldn't be saved using given '%s' terms and track ID #%d in %s",
                    $terms, $currentTrack->getId(), __METHOD__)));
            }

            return array($currentTrack, $broadcast, $this->terms, $albumModel, $trackList);
        }

        return array();
    }

    protected function saveBroadCastBeforeThrowingException($terms, $e) {
        list($code, $broadcast) = $this->saveBroadcast($terms, null);
        throw $e;
    }


    /**
     * @param $album
     * @param $images
     * @param $terms
     *
     * @return AlbumModel
     * @throws InvalidAlbumInputException
     */
    protected function saveAlbum($album, $images, $terms)
    {
        if(empty($terms)) {
            throw new InvalidAlbumInputException(sprintf("terms are null in %s", __METHOD__));
        }
        elseif(empty($album)) {
            throw new InvalidAlbumInputException(sprintf("album is null for '%s' terms in %s", $terms, __METHOD__));
        }
        elseif(empty($images)) {
            throw new InvalidAlbumInputException(sprintf("images list is null for '%s' terms in %s", $terms, __METHOD__));
        }
        $albumModel = new AlbumModel();
        try {
            $this->logger->info(sprintf('Trying to SAVE %s ALBUM INFOS using the TrackRetriever', $terms));
            $albumModel->fromXml($album);
            //$albumModel->setImagesfromXml($images);
            $albumModel->setPublished(true);

            $this->saveAlbumImages($albumModel, $images);

            $exists = $this->albumExistsYet($albumModel);
            if('RadioSolution\ProgramBundle\Entity\Album' === get_class($exists)) {
                $this->logger->info(sprintf(
                    'Album #%d "%s" already existing found for given "%s" terms',
                    $exists->getId(), $exists->getTitle(), $terms
                ));

                return array(self::ALREADY_EXISTS, $exists);
            }
            $this->entityManager->persist($albumModel);
            $this->entityManager->flush();
        } catch (InvalidAlbumInputException $e) {
            $this->logger->error(sprintf('An error occurred : %s', $e));
            return array(self::SOMETHING_TERRIBLE_HAPPENED, null);
        }

        return array(self::NEWLY_SAVED, $albumModel);
    }

    protected function saveAlbumImages(&$albumModel, $xml)
    {
        $values = (array) json_decode(json_encode($xml));
        if (!empty($values)
            && !empty($values["ImageSet"])
            && !empty($values["ImageSet"]->LargeImage)
            && !empty($values["ImageSet"]->LargeImage->URL)
        ) {
            $albumModel->setThumbnailUrl(strval($values["ImageSet"]->LargeImage->URL));
            $filename = explode('/', $albumModel->getThumbnailUrl());
            $filename = $filename[sizeof($filename) - 1];


            $fileDir = $this->container->getParameter('kernel.root_dir') . '/../web/uploads/images/albums/';
            if (!file_exists($fileDir)) {
                if (!mkdir($fileDir, 0770, true)) return;
            }
            $filePath = $fileDir . $filename;

            try {
                $ch = curl_init($albumModel->getThumbnailUrl());
                $fp = fopen($filePath, "w");

                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_HEADER, 0);

                curl_exec($ch);
                curl_close($ch);
                fclose($fp);

                $media = new \Application\Sonata\MediaBundle\Entity\Media();
                $media->setBinaryContent($filePath);
                $media->setContext('default');
                $media->setProviderName('sonata.media.provider.image');

                $albumModel->setImage($media);
            } catch (\Exception $e) {

            }
        }
    }

    /**
     * @param AlbumModel $album
     *
     * @return AlbumModel
     */
    public function albumExistsYet(AlbumModel $album)
    {
        $result = null;

        if (!isset($this->entityManager)) {
            $this->setUp();
        }

        return $this->entityManager->getRepository("ProgramBundle:Album")->findOneBy(
            array(
                'title' =>  $album->getTitle(),
                'artist' => $album->getArtist()
            )
        );
    }


    /**
     * @link http://stackoverflow.com/a/976712/490589
     * @param \DateTime $start_date
     * @param \DateTime $end_date
     * @param \DateTime $date_to_check
     *
     * @return bool
     */
    public function checkInRange($start_date, $end_date, $date_to_check)
    {
        // Convert to timestamp
        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        $user_ts = strtotime($date_to_check);

        // Check that user date is between start & end
        return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
    }

    /**
     * @param Track $track
     *
     * @return array
     * @throws InvalidTrackInputException
     */
    protected function saveBroadCast($terms, $track = null)
    {
        if (empty($terms)) {
            throw new InvalidTrackInputException(sprintf("terms cannot be null in %s", __METHOD__));
        }
        /** @var Broadcast */
        $lastBroadcast = $this
            ->container->get('doctrine')->getManager()
            ->getRepository('ProgramBundle:Broadcast')
            ->createQueryBuilder('b')
            ->orderBy('b.created_at', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        /** @var Track */
        if (!empty($lastBroadcast)) {
            $lastBroadcastedTrack = $lastBroadcast->getTrack();
        }

        // Broadcast track already processed in last db entry
        if (!empty($lastBroadcastedTrack) && !empty($track)) {
            if($track->getId() === $lastBroadcastedTrack->getId()) {
                return array(self::ALREADY_EXISTS, $lastBroadcast);
            }
        }
        // Broadcast terms already processed (in case track is not found) in last db entry
        if (!empty($lastBroadcast && !empty($terms))) {
            if($lastBroadcast->getTerms() === $terms) {
                return array(self::ALREADY_EXISTS, $lastBroadcast);
            }
        }
        $broadcast = new Broadcast();

        try {
            $broadcast->setBroadcasted(new \DateTime('now'));
            if (!empty($track)) {
                $this->logger->info(sprintf('Trying to SAVE broadcast using Track %d "%s"',
                    $track->getId(), $track->getTitle()));
                $broadcast->setTrack($track);
            } else {
                $this->logger->info(sprintf('Trying to SAVE broadcast using no Track but terms "%s"', $terms));
            }
            $broadcast->setTerms($terms);
            $this->entityManager->persist($broadcast);
            $this->entityManager->flush();

        } catch (InvalidAlbumInputException $e) {
            $this->logger->error(sprintf('An error occurred : %s', $e));
            return array(self::SOMETHING_TERRIBLE_HAPPENED, null);
        }

        return array(self::NEWLY_SAVED, $broadcast);
    }

    /**
     * @param Track[] $trackList
     * @param string $currentTrackTitle
     *
     * @return null
     * @throws InvalidTrackInputException
     */
    protected function getTrackByTitle($trackList = array(), $currentTrackTitle = '')
    {
        $found = null;
        if(empty($trackList) || empty($currentTrackTitle)) {
            throw new InvalidTrackInputException(sprintf('trackList or currentTrackTitle are empty in %s', __METHOD__));
        }

        foreach($trackList as $track) {
            if(trim(strtolower($currentTrackTitle)) == trim(strtolower($track->getTitle()))) {
                $found = $track;
                break;
            }
        }

        return $found;
    }

    /**
     * @param AlbumModel $albumModel
     * @param string     $terms
     * @param array      $tracks
     *
     * @return array
     */
    protected function saveTrackList(AlbumModel $albumModel, $terms = '', $tracks = array())
    {
        $trackList = array();
        if ($albumModel->getId()) {
            foreach ($tracks as $position => $title) {
                if ("titre inconnu" == trim(strtolower($title))) {
                    $this->logger->info(
                        sprintf('IGNORING %s - %s TRACK INFOS while processsing %s (album #%s) track list',
                            $terms, $title, $albumModel->getId()));
                    continue;
                }
                try {
                    $this->logger->info(sprintf('Trying to SAVE %s - %s TRACK INFOS using the TrackRetriever', $terms, $title));
                    $trackModel = new Track();
                    $trackModel->fromAlbum($albumModel, $title, $position+1); // position is an index's an index
                    $this->entityManager->persist($trackModel);
                    $this->entityManager->flush();
                    $trackList[] = $trackModel;
                } catch (InvalidAlbumInputException $e) {
                    $this->logger->error(sprintf('An error occurred : %s', $e));
                }
            }
        }

        return $trackList;
    }
}
