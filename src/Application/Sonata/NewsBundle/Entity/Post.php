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
     * @var string $short_title
     */
    protected $short_title;

    protected $type;

    /**
     * @var podcast $podcast
     */
    protected $podcast;

    /**
     * @var Application\Sonata\MediaBundle\Entity\Media
     */
    protected $image;

    protected $relatedPosts;

    public function __toString() {
        $str = !empty($this->getTitle()) ? $this->getTitle() : 'Titre inconnu';
        if ($this->getPublicationDateStart()) $str .= ' - ' . $this->getPublicationDateStart()->format('d/m/Y H:i');
        return $str;
    }
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

    /**
     * Gets the value of short_title.
     *
     * @return string $short_title
     */
    public function getShortTitle()
    {
        return $this->short_title;
    }

    /**
     * Sets the value of short_title.
     *
     * @param string $short_title $short_title the short title
     *
     * @return self
     */
    public function setShortTitle($short_title)
    {
        $this->short_title = $short_title;

        return $this;
    }

    public function addRelatedPost($post)
    {
        $this->relatedPosts[] = $post;
    }

    public function removedRelatedPost($post)
    {
        $this->relatedPosts->removeElement($post);
    }

    public function setRelatedPosts($relatedPost)
    {
        $this->relatedPosts = $relatedPost;

        return $this;
    }


    public function getRelatedPosts()
    {
        return $this->relatedPosts;
    }

    /**
     * Gets the value of type.
     *
     * @return mixed
     */
    public function getType()
    {
        if(empty($this->type)){
            return empty($this->getPodcast()) ? 'actualite' : 'podcast';
        }
        return $this->type;
    }

    /**
     * Sets the value of type.
     *
     * @param mixed $type the type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
