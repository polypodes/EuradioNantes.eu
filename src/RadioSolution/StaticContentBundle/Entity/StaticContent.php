<?php

namespace RadioSolution\StaticContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\ClassificationBundle\Model\Tag as Tag;

/**
 * RadioSolution\StaticContentBundle\Entity\StaticContent
 */
class StaticContent
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
     * @var string $introduction
     */
    private $introduction;

    /**
     * @var string $body
     */
    private $body;

    private $categoryStaticContent;

    private $order_content;

    /**
     * @var date $date_add
     */
    private $date_add;

    /**
     * @var Application\Sonata\MediaBundle\Entity\Media
     */
    private $image;

    /**
     * @var string $slug
     */
    private $slug;

    /**
     * @var boolean
     */
    private $published;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
    	$this->setDateAdd(new \DateTime);
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
     * Set introduction
     *
     * @param string $introduction
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;
    }

    /**
     * Get introduction
     *
     * @return string
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * Set body
     *
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
    /**
     * @var RadioSolution\StaticContentBundle\Entity\CategoryStaticContent
     */



    /**
     * Set CategoryStaticContent
     *
     * @param RadioSolution\StaticContentBundle\Entity\CategoryStaticContent $categoryStaticContent
     */
    public function setCategoryStaticContent(\RadioSolution\StaticContentBundle\Entity\CategoryStaticContent $categoryStaticContent)
    {
        $this->categoryStaticContent = $categoryStaticContent;
    }

    /**
     * Get CategoryStaticContent
     *
     * @return RadioSolution\StaticContentBundle\Entity\CategoryStaticContent
     */
    public function getCategoryStaticContent()
    {
        return $this->categoryStaticContent;
    }

    /**
     * Set date_add
     *
     * @param date $dateAdd
     */
    public function setDateAdd($dateAdd)
    {
        $this->date_add = $dateAdd;
    }

    /**
     * Get date_add
     *
     * @return date
     */
    public function getDateAdd()
    {
        return $this->date_add;
    }

    /**
     * Set image
     *
     * @param Application\Sonata\MediaBundle\Entity\Media $image
     */
    public function setImage(\Application\Sonata\MediaBundle\Entity\Media $image)
    {
        $this->image = $image;
    }

    /**
     * Get image
     *
     * @return Application\Sonata\MediaBundle\Entity\Media
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
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

    public function getOrderContent(){
    	return $this->order_content;
    }

    public function setOrderContent($order_content){
    	$this->order_content=$order_content;
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
}
