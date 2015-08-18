<?php

namespace RadioSolution\ProgramBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * RadioSolution\ProgramBundle\Entity\Label
 */
class Label
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var datetime
     */
    private $date;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $resume;
    /**
     * @var string
     */
    private $content;

    /**
     * @var RadioSolution\ProgramBundle\Entity\Album
     */
    private $albums;

    /**
     * @var Application\Sonata\MediaBundle\Entity\Media
     */
    private $image;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var boolean
     */
    private $published;

    public function __construct()
    {
        $this->date = new \DateTime('now');
        $this->albums = new ArrayCollection();
    }

    /**
     * to string
     *
     * @return string
     */
    public function __toString()
    {
        return !empty($this->getTitle()) ? $this->getTitle() : 'Untitled';
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param datetime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return datetime
     */
    public function getDate()
    {
        return $this->date;
    }

    public function getSlug()
    {
        return $this->slug();
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getResume()
    {
        return $this->resume;
    }

    public function setResume($resume)
    {
        $this->resume = $resume;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getPublished()
    {
        return $this->published;
    }

    public function setPublished($published)
    {
        $this->published = $published;
        return $this;
    }

    public function getAlbums()
    {
        return $this->albums;
    }

    public function setAlbums($albums)
    {
        foreach ($albums as $album) {
            $this->addAlbum($album);
        }
        return $this;
    }

    public function addAlbum($album)
    {
        $album->setLabelId($this);
        $this->albums[] = $album;
        return $this;
    }

    public function removeAlbum($album)
    {
        $album->setLabelId(null);
        $this->albums->removeElement($album);
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }
}
