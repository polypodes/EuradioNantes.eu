<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\NewsBundle\Entity;

use Sonata\NewsBundle\Entity\BasePost as BasePost;

/**
 * This file has been generated by the Sonata EasyExtends bundle ( http://sonata-project.org/bundles/easy-extends )
 *
 * References :
 *   working with object : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 *
 * @author <yourname> <youremail>
 */
class Post extends BasePost
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var podcast $podcast
     */
    protected $podcast;

    /**
     * @var Application\Sonata\MediaBundle\Entity\Media
     */
    protected $image;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get podcast
     *
     * @return Podcast $podcast
     */
    public function getPodcast()
    {
        return $this->podcast;
    }


    public function getFilePodcast()
    {
        return $this->podcast->getFilePodcast()->getProviderReference();
    }

    public function getMediaPodcast()
    {
        if ($this->podcast!=NULL)
        return $this->podcast->getFilePodcast();
    }

    /**
     * Get podcast
     *
     * @return Podcast $podcast
     */
    public function setPodcast(\RadioSolution\PodcastBundle\Entity\Podcast $podcast)
    {
        $this->podcast=$podcast;
    }

    public function getImage()
    {

        if(!$this->image){

            if($this->getPodcast()){

                $podcast = $this->getPodcast();

                if($podcast->getEmission()){

                    $this->image =$podcast->getImageEmission();

                }

            }

        }

        return $this->image;

    }

    public function setImage($image)
    {
        $this->image = $image;
    }
}
