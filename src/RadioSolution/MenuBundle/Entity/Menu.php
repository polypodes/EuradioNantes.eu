<?php

namespace RadioSolution\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * RadioSolution\MenuBundle\Entity\Menu
 */
class Menu
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
     * @var ArrayCollection
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
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

    public function getItems()
    {
        return $this->items;
    }

    public function addItem(Item $item)
    {
        $this->items[] = $item;
    }

    public function removeItem(Item $item)
    {
        $this->items->removeElement($item);

        return $this;
    }

}
