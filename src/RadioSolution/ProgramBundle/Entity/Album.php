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
 * RadioSolution\ProgramBundle\Entity\Emission.
 */
class Album
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $artist;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $manufacturer;

    /**
     * @var ArrayCollection
     */
    private $playlists;

    /**
     * @var string
     */
    private $publisher;

    /**
     * @var \DateTime
     */
    private $releaseDate;

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
    private $studio;

    /**
     * @var string
     */
    private $title;

    /**
     * @var
     * @example http://ecx.images-amazon.com/images/I/61z54MQNt5L._SL75_.jpg
     */
    private $thumbnailUrl;

    /**
     * @var ArrayCollection of Track[]
     */
    private $tracks;

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
        $this->tracks = new ArrayCollection();
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
     * @return Album
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param \SimpleXMLElement $xml
     *
     * @return $this
     */
    public function setImagesFromXml(\SimpleXMLElement $xml)
    {
        $values = (array) json_decode(json_encode($xml));
        if(
            !empty($values)
            && !empty($values["ImageSet"])
            && !empty($values["ImageSet"]->ThumbnailImage)
            && !empty($values["ImageSet"]->ThumbnailImage->URL)
        ) {
           $this->thumbnailUrl = strval($values["ImageSet"]->ThumbnailImage->URL);
        }

        return $this;
    }

    /**
     * @param \SimpleXMLElement $xml
     *
     * @return $this
     *
     * @throws InvalidAlbumInputException
     */
    public function fromXml(\SimpleXMLElement $xml)
    {
        $attrs = get_object_vars($this);
        $unsetAttrs = array(
            "id", "created_at", "updated_at", "tracks", "playlists",
            "featuredFrom", "featuredTo", "thumbnailUrl");
        foreach($unsetAttrs as $attrKey) {
            unset($attrs[$attrKey]);
        }
        $keys = array_map("ucfirst", array_keys($attrs));
        $values = (array) json_decode(json_encode($xml)); // it's a simple XML obj, no depth.
        $values["Artist"] = $values["Creator"]; // return both keys

        foreach ($keys as $key) {
            if (!isset($values[$key]) || !is_string($values[$key]) || empty($values[$key])) {
                throw new InvalidAlbumInputException(
                    sprintf("In %s, xml input must contain a %s value", __METHOD__, $key));
            }
            $attr = lcfirst($key); // mind camelCased attributes likes "releaseDate"
            $this->$attr = $values[$key];
        }

        $this->releaseDate = \DateTime::createFromFormat("Y-m-d", $this->releaseDate);
        $this->created_at = $this->updated_at = new \DateTime();

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
     * Add track.
     *
     * @param Track $track
     *
     * @return Album
     */
    public function addTrack(Track $track)
    {
        $this->tracks[] = $track;

        return $this;
    }

    /**
     * Remove track.
     *
     * @param Track $track
     */
    public function removeTrack(Track $track)
    {
        $this->tracks->removeElement($track);
    }

    /**
     * Get tracks.
     *
     * @return ArrayCollection
     */
    public function getTracks()
    {
        return $this->tracks;
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
     * @return Album
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
     * @return Album
     */
    public function setFeaturedTo($featuredTo)
    {
        $this->featuredTo = $featuredTo;

        return $this;
    }

    /**
     * Add playlist
     *
     * @param \RadioSolution\ProgramBundle\Entity\Playlist $playlist
     *
     * @return Album
     */
    public function addPlaylist(\RadioSolution\ProgramBundle\Entity\Playlist $playlist)
    {
        $this->playlists[] = $playlist;

        return $this;
    }

    /**
     * Remove playlist
     *
     * @param \RadioSolution\ProgramBundle\Entity\Playlist $playlist
     */
    public function removePlaylist(\RadioSolution\ProgramBundle\Entity\Playlist $playlist)
    {
        $this->playlists->removeElement($playlist);
    }

    /**
     * Get playlists
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlaylists()
    {
        return $this->playlists;
    }

    /**
     * Set thumbnailUrl
     *
     * @param string $thumbnailUrl
     *
     * @return Album
     */
    public function setThumbnailUrl($thumbnailUrl)
    {
        $this->thumbnailUrl = $thumbnailUrl;

        return $this;
    }

    /**
     * Get thumbnailUrl
     *
     * @return string
     */
    public function getThumbnailUrl()
    {
        return $this->thumbnailUrl;
    }
}
