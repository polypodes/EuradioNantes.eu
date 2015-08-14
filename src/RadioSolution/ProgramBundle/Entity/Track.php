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
 * File created by ronan@lespolypodes.com (07/08/2015 - 14:40)
 */

namespace RadioSolution\ProgramBundle\Entity;

use RadioSolution\ProgramBundle\Exception\InvalidAlbumInputException;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * RadioSolution\ProgramBundle\Entity\Track.
 */
class Track
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var Album
     */
    private $album;

    /**
     * @var string
     */
    private $artist;

    /**
     * @var ArrayCollection of BroadCast[]
     */
    private $broadcasts;

    /**
     * @var string
     */
    private $genre;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $manufacturer;

    /**
     * @var string
     */
    private $publisher;

    /**
     * @var \DateTime
     */
    private $releaseDate;

    /**
     * @var integer
     */
    private $runningTime;

    /**
     * @var string
     */
    private $studio;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $trackSequence;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime
     */
    private $updated_at;

    public function __toString()
    {
        $str = (isset($this->title)) ? $this->title : 'Titre non défini';

        return $str;
    }

    /**
     * Get id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set creator.
     *
     * @param string $creator
     *
     * @return Album
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator.
     *
     * @return string
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Get genre.
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set genre.
     *
     * @param string $genre
     *
     * @return Album
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set label.
     *
     * @param string $label
     *
     * @return Album
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get manufacturer.
     *
     * @return string
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * Set manufacturer.
     *
     * @param string $manufacturer
     *
     * @return Album
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * Get publisher.
     *
     * @return string
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * Set publisher.
     *
     * @param string $publisher
     *
     * @return Album
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * Get releaseDate.
     *
     * @return \DateTime
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * Set releaseDate.
     *
     * @param \DateTime $releaseDate
     *
     * @return Album
     */
    public function setReleaseDate($releaseDate)
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * Get studio.
     *
     * @return string
     */
    public function getStudio()
    {
        return $this->studio;
    }

    /**
     * Set studio.
     *
     * @param string $studio
     *
     * @return Album
     */
    public function setStudio($studio)
    {
        $this->studio = $studio;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Album
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return Album
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get artist.
     *
     * @return string
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set artist.
     *
     * @param string $artist
     *
     * @return Album
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Get runningTime.
     *
     * @return integer
     */
    public function getRunningTime()
    {
        return $this->runningTime;
    }

    /**
     * Set runningTime.
     *
     * @param integer $runningTime
     *
     * @return Track
     */
    public function setRunningTime($runningTime)
    {
        $this->runningTime = $runningTime;

        return $this;
    }

    /**
     * Get trackSequence.
     *
     * @return integer
     */
    public function getTrackSequence()
    {
        return $this->trackSequence;
    }

    /**
     * Set trackSequence.
     *
     * @param integer $trackSequence
     *
     * @return Track
     */
    public function setTrackSequence($trackSequence)
    {
        $this->trackSequence = $trackSequence;

        return $this;
    }

    /**
     * Get album.
     *
     * @return Album
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * Set album.
     *
     * @param Album $album
     *
     * @return Track
     */
    public function setAlbum(Album $album = null)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * @param Album  $album
     * @param string $title
     * @param int    $position
     *
     * @return $this
     *
     * @throws InvalidAlbumInputException
     */
    public function fromAlbum(Album $album, $title = "", $position = null)
    {
        $this->setArtist($album->getArtist());
        $this->setLabel($album->getLabel());
        $this->setManufacturer($album->getManufacturer());
        $this->setPublisher($album->getPublisher());
        $this->setReleaseDate($album->getReleaseDate());
        $this->setStudio($album->getStudio());
        $this->setAlbum($album);
        if (!empty($title)) {
            $this->setTitle($title);
        }
        if ($position >= 0) {
            $this->setTrackSequence($position);
        }

        return $this;
    }

    /**
     * set datetimes on create/update.
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new \DateTime('now'));
        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Album
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->broadcasts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add broadcast
     *
     * @param \RadioSolution\ProgramBundle\Entity\Broadcast $broadcast
     *
     * @return Track
     */
    public function addBroadcast(\RadioSolution\ProgramBundle\Entity\Broadcast $broadcast)
    {
        $this->broadcasts[] = $broadcast;

        return $this;
    }

    /**
     * Remove broadcast
     *
     * @param \RadioSolution\ProgramBundle\Entity\Broadcast $broadcast
     */
    public function removeBroadcast(\RadioSolution\ProgramBundle\Entity\Broadcast $broadcast)
    {
        $this->broadcasts->removeElement($broadcast);
    }

    /**
     * Get broadcasts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBroadcasts()
    {
        return $this->broadcasts;
    }
}
