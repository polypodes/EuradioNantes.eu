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

class Broadcast
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var Track
     */
    private $track;

    /**
     * @var \DateTime
     */
    private $broadcasted;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime
     */
    private $updated_at;

    /**
     * @return string
     */
    public function __toString()
    {
        return (!empty($this->broadcasted)) ? $this->broadcasted : "Aucune information de diffusion";
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set broadcasted
     *
     * @param \DateTime $broadcasted
     *
     * @return Broadcast
     */
    public function setBroadcasted($broadcasted)
    {
        $this->broadcasted = $broadcasted;

        return $this;
    }

    /**
     * Get broadcasted
     *
     * @return \DateTime
     */
    public function getBroadcasted()
    {
        return $this->broadcasted;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Broadcast
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Broadcast
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set track
     *
     * @param Track $track
     *
     * @return Broadcast
     */
    public function setTrack(Track $track = null)
    {
        $this->track = $track;

        return $this;
    }

    /**
     * Get track
     *
     * @return \RadioSolution\ProgramBundle\Entity\Track
     */
    public function getTrack()
    {
        return $this->track;
    }
}
