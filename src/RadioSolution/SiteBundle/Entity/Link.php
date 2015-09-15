<?php
// src/Acme/UrlShortenerBundle/Entity/Link.php

namespace RadioSolution\SiteBundle\Entity;

use Mremi\UrlShortenerBundle\Entity\Link as BaseLink;

class Link extends BaseLink
{
    /**
     * @var integer
     */
    protected $id;
}
