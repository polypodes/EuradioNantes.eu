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

use Doctrine\Common\Collections\ArrayCollection;

/**
 * RadioSolution\ProgramBundle\Entity\Emission.
 */
class Playlist
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $featuredFrom;

    /**
     * @var \DateTime
     */
    private $featuredTo;

    /**
     * @var string
     */
    private $title;

    /**
     * @var ArrayCollection of Album[]
     */
    private $albums;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime
     */
    private $updated_at;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->playlists = new ArrayCollection();
        $this->albums = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (!empty($this->getTitle())) ? $this->getTitle() : 'Titre non défini';
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
     * @return Playlist
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
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
     * @return Playlist
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Add album.
     *
     * @param Album $album
     *
     * @return Playlist
     */
    public function addAlbum(Album $album)
    {
        $this->albums[] = $album;

        return $this;
    }

    /**
     * Remove album.
     *
     * @param Album $album
     */
    public function removeAlbum(Album $album)
    {
        $this->albums->removeElement($album);
    }

    /**
     * Get albums.
     *
     * @return ArrayCollection
     */
    public function getAlbums()
    {
        return $this->albums;
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
     * @return Playlist
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get featuredFrom.
     *
     * @return \DateTime
     */
    public function getFeaturedFrom()
    {
        return $this->featuredFrom;
    }

    /**
     * Set featuredFrom.
     *
     * @param \DateTime $featuredFrom
     *
     * @return Playlist
     */
    public function setFeaturedFrom($featuredFrom)
    {
        $this->featuredFrom = $featuredFrom;

        return $this;
    }

    /**
     * Get featuredTo.
     *
     * @return \DateTime
     */
    public function getFeaturedTo()
    {
        return $this->featuredTo;
    }

    /**
     * Set featuredTo.
     *
     * @param \DateTime $featuredTo
     *
     * @return Playlist
     */
    public function setFeaturedTo($featuredTo)
    {
        $this->featuredTo = $featuredTo;

        return $this;
    }
}
