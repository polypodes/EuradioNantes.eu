<?php

namespace RadioSolution\ContactBundle\Entity;

class Contact
{
    private $id;

    private $title;

    private $name;

    private $email;

    private $position;

    private $created_at;

    private $updated_at;

    public function __toString()
    {
        return !empty($this->title) ? $this->title : 'Contact inconnu';
    }

    public function prePersist()
    {
        $this->updated_at = $this->created_at = new \Datetime();
    }

    public function preUpdate()
    {
        $this->updated_at = new \Datetime();
    }

    public function getId()
    {
        return $this->id;
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
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
}
