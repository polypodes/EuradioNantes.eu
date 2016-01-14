<?php

namespace RadioSolution\PodcastBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RadioSolution\ProgramBundle\Entity\Program;

/**
 * RadioSolution\PodcastBundle\Entity\Podcast
 */
class Podcast
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
     * @var datetime $real_time_start
     */
    private $real_time_start;

    /**
     * @var Application\Sonata\MediaBundle\Entity\Media
     */
    private $filePodcast;

    private $dlAuth;

    /**
     * @var Application\Sonata\NewsBundle\Entity\Post
     */
    private $post;

    private $home_page;

    /**
     * @var RadioSolution\ProgramBundle\Entity\Program
     */
    private $program;

    /**
     * Marqueurs temporels de lecture du Podcast
     * @var array
     */
    private $markers;


    public function __construct()
    {
    	$this->real_time_start = new \DateTime('now');
    	$this->dlAuth = true;

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

    /**
     * Set real_time_start
     *
     * @param datetime $realTimeStart
     */
    public function setRealTimeStart($realTimeStart)
    {
        $this->real_time_start = $realTimeStart;
    }

    /**
     * Get real_time_start
     *
     * @return datetime
     */
    public function getRealTimeStart()
    {
        return $this->real_time_start;
    }



    /**
     * Set dlauth
     *
     * @param Application\Sonata\MediaBundle\Entity\Media $filePodcast
     */
    public function setDlAuth($dlAuth)
    {
    	$this->dlAuth = $dlAuth;
    }

    /**
     * Get filePodcast
     *
     * @return Application\Sonata\MediaBundle\Entity\Media
     */
    public function getDlAuth()
    {
    	return $this->dlAuth;
    }




    /**
     * Set filePodcast
     *
     * @param Application\Sonata\MediaBundle\Entity\Media $filePodcast
     */
    public function setFilePodcast($filePodcast)
    {
        $this->filePodcast = $filePodcast;
    }

    /**
     * Get filePodcast
     *
     * @return Application\Sonata\MediaBundle\Entity\Media
     */
    public function getFilePodcast()
    {
        return $this->filePodcast;
    }

    /**
     * Set post
     *
     * @param Application\Sonata\NewsBundle\Entity\Post $post
     */
    public function setPost(\Application\Sonata\NewsBundle\Entity\Post $post)
    {
        $post->setType('podcast');
        $this->post = $post;
    }

    /**
     * Get post
     *
     * @return Application\Sonata\NewsBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }
    //public function getPostTitle()
    //{
    //	return $this->post->getTitle();
    //}
    //public function getPostAbstract()
    //{
    //	return $this->post->getAbstract();
    //}
    //public function getPostImage()
    //{
    //	return $this->post->getImage();
    //}
    public function getSlug()
    {
    	return $this->post->getYear().'/'.$this->post->getMonth().'/'.$this->post->getDay().'/'.$this->post->getSlug();
    }
    public  function __toString(){
    	return $this->getName();
    }

    /**
     * Set program
     *
     * @param RadioSolution\ProgramBundle\Entity\Program $program
     */
    public function setProgram($program)
    {
        if (!empty($this->program)) {
            $this->program->setPodcast(null);
        }
        if ($program) {
            $program->setPodcast($this);
        }
        $this->program = $program;
    }

    /**
     * Get program
     *
     * @return RadioSolution\ProgramBundle\Entity\Program
     */
    public function getProgram()
    {
        return $this->program;
    }

    public function getEmission()
    {
        return $this->program ? $this->program->getEmission()->getName() : null;
    }

    public function getImageEmission()
    {
    	return $this->program->getEmission()->getMedia();
    }

    public function getHomePage(){
    	return $this->home_page;
    }

    public function setHomePage($homePage){
    	$this->home_page=$homePage;
    }

    public function getMarkers()
    {
        return $this->markers;
    }

    public function getMarkersJSON()
    {
        $data = array(
            'regions' => array()
        );
        if (!empty($this->markers)) {
            foreach ($this->markers as $marker) {
                if (empty($marker['start']) || empty($marker['title'])) {
                    continue;
                }
                // convert start time hh:mm to seconds
                $start = $marker['start'];
                sscanf($start, "%d:%d", $minutes, $seconds);
                $start = $minutes * 60 + $seconds;

                // convert end time hh:mm to seconds
                if (!empty($marker['end'])) {
                    $end = $marker['end'];
                    sscanf($end, "%d:%d", $minutes, $seconds);
                    $end = $minutes * 60 + $seconds;
                } else {
                    $end = $start;
                }

                $title = $marker['title'];
                $text = $marker['text'];

                $data['regions'][] = array(
                    'start' => $start,
                    'end' => $end,
                    'color' => 'rgba(0, 0, 0, 0.03)',
                    'data' => array(
                        'title' => $title,
                        'text' => $text,
                    ),
                    'drag' => false
                );
            }
        }
        //{&quot;regions&quot;: [{&quot;start&quot;: 1, &quot;end&quot;: 13, &quot;color&quot;: &quot;rgba(0, 0, 0, 0.03)&quot;, &quot;data&quot;: {&quot;title&quot;: &quot;Interview de Patrick Boulote&quot;, &quot;text&quot;: &quot;Brooklyn Neutra asymmetrical cred Carles raw denim. Cronut squid aesthetic, shabby chic iPhone organic chia messenger bag distillery McSweeney's literally bicycle rights Shoreditch put a bird on it. Cliche authentic..&quot;}},{&quot;start&quot;: 18, &quot;end&quot;: 33, &quot;color&quot;: &quot;rgba(0, 0, 0, 0.03)&quot;, &quot;data&quot;: {&quot;title&quot;: &quot;Interview de Patou gros&quot;, &quot;text&quot;: &quot;Brooklyn Neutra asymmetrical cred Carles raw denim. Cronut squid aesthetic, shabby chic iPhone organic chia messenger bag distillery McSweeney's literally bicycle rights Shoreditch put a bird on it. Cliche authentic..&quot;}},{&quot;start&quot;: 100, &quot;end&quot;: 113, &quot;color&quot;: &quot;rgba(0, 0, 0, 0.03)&quot;, &quot;data&quot;: {&quot;title&quot;: &quot;Interview de Seb Castagne&quot;, &quot;text&quot;: &quot;Brooklyn Neutra asymmetrical cred Carles raw denim. Cronut squid aesthetic, shabby chic iPhone organic chia messenger bag distillery McSweeney's literally bicycle rights Shoreditch put a bird on it. Cliche authentic..&quot;}},{&quot;start&quot;: 150, &quot;end&quot;: 183, &quot;color&quot;: &quot;rgba(0, 0, 0, 0.03)&quot;, &quot;data&quot;: {&quot;title&quot;: &quot;Témoignage d'Eric Gras&quot;, &quot;text&quot;: &quot;Brooklyn Neutra asymmetrical cred Carles raw denim. Cronut squid aesthetic, shabby chic iPhone organic chia messenger bag distillery McSweeney's literally bicycle rights Shoreditch put a bird on it. Cliche authentic..&quot;}},{&quot;start&quot;: 190, &quot;end&quot;: 213, &quot;color&quot;: &quot;rgba(0, 0, 0, 0.03)&quot;, &quot;data&quot;: {&quot;title&quot;: &quot;Interview de Francis Bold&quot;, &quot;text&quot;: &quot;Brooklyn Neutra asymmetrical cred Carles raw denim. Cronut squid aesthetic, shabby chic iPhone organic chia messenger bag distillery McSweeney's literally bicycle rights Shoreditch put a bird on it. Cliche authentic..&quot;}},{&quot;start&quot;: 23, &quot;end&quot;: 43, &quot;color&quot;: &quot;rgba(0, 0, 0, 0.03)&quot;, &quot;data&quot;: {&quot;title&quot;: &quot;text2&quot;, &quot;text&quot;: &quot;lorem ipsum bla&quot;}}]}
        return json_encode($data);
    }

    public function setMarkers($markers)
    {
        $this->markers = $markers;
        return $this;
    }
}
