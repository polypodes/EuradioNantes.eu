<?php

namespace RadioSolution\ProgramBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\ClassificationBundle\Model\Tag as Tag;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * RadioSolution\ProgramBundle\Entity\Emission
 */
class Emission
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $description
     */
    private $description;

    /**
     * @var RadioSolution\ProgramBundle\Entity\EmissionTheme
     */
    private $theme;

    /**
     * @var Application\Sonata\ClassificationBundle\Entity\Collection
     */
    private $collection;

    /**
     * @var RadioSolution\ProgramBundle\Entity\EmissionFrequency
     */
    private $group;

    /**
     * @var Application\Sonata\MediaBundle\Entity\Media
     */
    private $media;

    /**
     * @var date $diffusion_start
     */
    private $diffusion_start;

    /**
     * @var date $diffusion_stop
     */
    private $diffusion_stop;

    /**
     * @var ArrayCollection
     */
    private $exceptionalBroadcasts;

    /**
     * @var ArrayCollection
     */
    private $weeklyBroadcasts;

    /**
     * @var ArrayCollection
     */
    private $programs;

    /**
     * @var boolean $archive
     */
    private $archive;

    /**
     * @var RadioSolution\ProgramBundle\Entity\EmissionFrequency
     */
    private $frequency;

    /**
     * @var string $slug
     */
    private $slug;

   /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime
     */
    private $updated_at;

    public function __construct()
    {
        $this->exceptionalBroadcasts = new ArrayCollection();
        $this->weeklyBroadcasts = new ArrayCollection();
        $this->programs = new ArrayCollection();

        if (empty($this->created_at)) {
            $this->created_at = new \Datetime();
        }
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
        //die(var_dump($this->WeeklyBroadcast->first()));
        $this->updateRelated();
    }

    /**
     * to string
     *
     * @return string
     */
    public function __toString()
    {
    	return $this->getName();
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->setSlug(Tag::slugify($name));
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set theme
     *
     * @param RadioSolution\ProgramBundle\Entity\EmissionTheme $theme
     */
    public function setTheme(\RadioSolution\ProgramBundle\Entity\EmissionTheme $theme)
    {
        $this->theme = $theme;
        return $this;
    }
   /**
     * Get theme
     *
     * @return RadioSolution\ProgramBundle\Entity\EmissionTheme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function setCollection($collection)
    {
        $this->collection = $collection;
        return $this;
    }


    /**
     * Set group
     *
     * @param Application\Sonata\UserBundle\Entity\Group $group
     */
    public function setGroup(\Application\Sonata\UserBundle\Entity\Group $group)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * Get group
     *
     * @return Application\Sonata\UserBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set media
     *
     * @param Application\Sonata\MediaBundle\Entity\Media $media
     */
    public function setMedia(\Application\Sonata\MediaBundle\Entity\Media $media)
    {
        $this->media = $media;
        return $this;
    }

    /**
     * Get media
     *
     * @return Application\Sonata\MediaBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Add ExceptionalBroadcast
     *
     * @param RadioSolution\ProgramBundle\Entity\ExceptionalBroadcast $exceptionalBroadcast
     */
    public function addExceptionalBroadcast(\RadioSolution\ProgramBundle\Entity\ExceptionalBroadcast $exceptionalBroadcast)
    {
        $exceptionalBroadcast->setEmission($this);
        $this->exceptionalBroadcasts[] = $exceptionalBroadcast;
        return $this;
    }

    public function removeExceptionalBroadcast(\RadioSolution\ProgramBundle\Entity\ExceptionalBroadcast $exceptionalBroadcast)
    {
        //$exceptionalBroadcast->setEmission(null);
        $this->exceptionalBroadcasts->removeElement($exceptionalBroadcast);
        return $this;
    }

    /**
     * set ExceptionalBroadcast
     *
     * @param RadioSolution\ProgramBundle\Entity\ExceptionalBroadcast $exceptionalBroadcast
     */
    //public function setExceptionalBroadcast(\Doctrine\Common\Collections\ArrayCollection $exceptionalBroadcast)
    //{
    //	$this->ExceptionalBroadcast = $exceptionalBroadcast;
    //	return $this->ExceptionalBroadcast;
    //}

    /**
     * Get ExceptionalBroadcast
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getExceptionalBroadcasts()
    {
        return $this->exceptionalBroadcasts;
    }

    /**
     * Add WeeklyBroadcast
     *
     * @param RadioSolution\ProgramBundle\Entity\WeeklyBroadcast $weeklyBroadcast
     */
    public function addWeeklyBroadcast(\RadioSolution\ProgramBundle\Entity\WeeklyBroadcast $weeklyBroadcast)
    {
        $weeklyBroadcast->setEmission($this);
        $this->weeklyBroadcasts[] = $weeklyBroadcast;
        return $this;
    }

    public function removeWeeklyBroadcast(\RadioSolution\ProgramBundle\Entity\WeeklyBroadcast $weeklyBroadcast)
    {
        //$weeklyBroadcast->setEmission();
        $this->weeklyBroadcasts->removeElement($weeklyBroadcast);
        return $this;
    }

    /**
     * Add WeeklyBroadcast
     *
     * @param RadioSolution\ProgramBundle\Entity\WeeklyBroadcast $weeklyBroadcast
     */
    //public function setWeeklyBroadcast($weeklyBroadcast)
    //{
    //	$this->weeklyBroadcasts = $weeklyBroadcast;
    //	return $this;
    //}

    /**
     * Get WeeklyBroadcast
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getWeeklyBroadcasts()
    {
        return $this->weeklyBroadcasts;
    }

    /**
     * Set diffusion_start
     *
     * @param date $diffusionStart
     */
    public function setDiffusionStart()
    {
    	$now= new \DateTime('now',new \DateTimeZone('GMT'));
    	$now->setTime('00','00');
        $this->diffusion_start =  $now;
        return $this;
    }

    /**
     * Get diffusion_start
     *
     * @return date
     */
    public function getDiffusionStart()
    {
        return $this->diffusion_start;
    }

    /**
     * Set difusion_stop
     *
     * @param date $difusionStop
     */
    public function setDiffusionStop($diffusionStop)
    {
        $this->diffusion_stop = $diffusionStop;
        return $this;
    }

    /**
     * Get difusion_stop
     *
     * @return date
     */
    public function getDiffusionStop()
    {
        return $this->diffusion_stop;
    }

    /**
     * Set archive
     *
     * @param boolean $archive
     */
    public function setArchive($archive)
    {
        $this->archive = $archive;
        return $this;
    }

    /**
     * Get archive
     *
     * @return boolean
     */
    public function getArchive()
    {
        return $this->archive;
    }

    /**
     * Set frequency
     *
     * @param RadioSolution\ProgramBundle\Entity\EmissionFrequency $frequency
     */
    public function setFrequency(\RadioSolution\ProgramBundle\Entity\EmissionFrequency $frequency)
    {
        $this->frequency = $frequency;
        return $this;
    }

    /**
     * Get frequency
     *
     * @return RadioSolution\ProgramBundle\Entity\EmissionFrequency
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function getPrograms()
    {
        return $this->programs;
    }

    public function setPrograms($programs)
    {
        $this->programs = $programs;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function updateRelated()
    {

        if (!empty($this->weeklyBroadcasts)) {
            foreach($this->weeklyBroadcasts as $broadcast) {
                $broadcast->setEmission($this);
            }
        }
        if (!empty($this->exceptionalBroadcasts)) {
            foreach($this->exceptionalBroadcasts as $broadcast) {
                $broadcast->setEmission($this);
            }
        }
        //if (!empty($this->programs)) {
        //    foreach($this->programs as $program) {
        //        $program->setEmission($this);
        //    }
        //}

    }
}
