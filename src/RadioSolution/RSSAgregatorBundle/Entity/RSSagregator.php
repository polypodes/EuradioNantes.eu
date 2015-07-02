<?php

namespace RadioSolution\RSSAgregatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RadioSolution\RSSAgregatorBundle\Entity\RSSagregator
 */
class RSSagregator
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
    
    public function __toString(){
    	return $this->name;
    }
}