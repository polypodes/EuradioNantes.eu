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
 * File created by ronan@lespolypodes.com (04/08/2015 - 17:07)
 */

namespace RadioSolution\ProgramBundle\Service\Tracks;

use ApaiIO\Operations\Lookup;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;
use ApaiIO\ApaiIO;

/**
 * Class TrackRetriever.
 *
 * @url http://docs.aws.amazon.com/fr_fr/AWSECommerceService/latest/DG/ItemSearch.html
 */
class TrackRetriever implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface|null
     */
    protected $container = null;

    /**
     * @var GenericConfiguration|null
     */
    protected $conf = null;

    /**
     * @var ApaiIO|null
     */
    protected $apaiIO = null;

    /**
     * @param ContainerInterface $container
     * @param string             $locale
     * @param string             $amazon_ws_api_key
     * @param string             $amazon_ws_api_secret_key
     * @param string             $amazon_ws_api_associate_tag
     */
    public function __construct(ContainerInterface $container, $locale,
        $amazon_ws_api_key, $amazon_ws_api_secret_key, $amazon_ws_api_associate_tag)
    {
        $this->setContainer($container);
        $this->conf = new GenericConfiguration();
        $this->conf
            ->setCountry($locale) // mind that we assume that Sf2 locale == Amazon locale
            ->setAccessKey($amazon_ws_api_key)
            ->setSecretKey($amazon_ws_api_secret_key)
            ->setAssociateTag($amazon_ws_api_associate_tag);
        $this->apaiIO = new ApaiIO($this->conf);
    }

    /**
     * @param ContainerInterface|NULL $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        if (empty($container)) {
            throw new \InvalidArgumentException(sprintf("container argument passed to %s cannot be null", __METHOD__));
        }
        $this->container = $container;
    }

    /**
     * @param string $terms
     *
     * @return array
     *
     * @throws \Exception
     */
    public function search($terms = "")
    {
        if (empty($terms) || !is_string($terms)) {
            throw new \InvalidArgumentException(sprintf("terms argument passed to %s must be a string and cannot be null", __METHOD__));
        }
        list($artist, $title, $albumName) = $this->prepareItems($terms);
        $tracks = $album = $images = null;

        $search = new Search();
        $search
            ->setCategory('MP3Downloads')// DigitalMusic, MusicTracks, Music, MP3Downloads
            ->setCondition('All')// New (default) | Used | Collectible | Refurbished | All
            ->setRelationshipType('Tracks')
            ->setResponseGroup(array(
                'RelatedItems',
                'ItemAttributes',
                'Images',
            ))// Tracks won't work here
            ->setTitle($title)
            ->setKeywords(sprintf("%s %s %s", $artist, $albumName, $title));
        $xmlResponse = $this->apaiIO->runOperation($search);
        $xml         = new \SimpleXMLElement($xmlResponse);

        $result = isset($xml->Items->Item->ItemAttributes) ? $xml->Items->Item->ItemAttributes : null;

        // In cas this i not the original Artist' album but a compilation instead
        if(!$result) { // In cas this i not the original Artist' album but a compilation instead
            $xml = $this->searchbyAlbumAndTitle($albumName, $title);
            $result = isset($xml->Items->Item->ItemAttributes) ? $xml->Items->Item->ItemAttributes : null;
        }

        $images = isset($xml->Items->Item->ImageSets) ? $xml->Items->Item->ImageSets : null;

        if (isset($xml->Items->Item->ItemAttributes->ProductGroup) && "Digital Music Album" === strval($xml->Items->Item->ItemAttributes->ProductGroup)) {
            $albumName = strval($xml->Items->Item->ItemAttributes->Title);
            $album =  $xml->Items->Item->ItemAttributes;
        } elseif (isset($xml->Items->Item->RelatedItems->Relationship) && "Parents" === strval($xml->Items->Item->RelatedItems->Relationship)) {
            $albumName = strval($xml->Items->Item->RelatedItems->RelatedItem->Item->ItemAttributes->Title);
            $album =  $xml->Items->Item->RelatedItems->RelatedItem->Item->ItemAttributes;
        }
        // Tracks list search + second chance for albums images & details
        if (!$images || !$album || !$tracks) {
            if ($albumName) {
                $xml = $this->tracksSearch($artist, $albumName);
                if (isset($xml)) {
                    list($album, $images, $tracks) = $this->getDetails($xml, $title, $album, $images, $tracks);
                }
            }
        }

        //second chance, with track title added in Keywords
        if (!$images || !$album || !$tracks) {
            if ($albumName) {
                $xml = $this->tracksSearch($artist, $albumName, true);
                if (isset($xml)) {
                    list($album, $images, $tracks) = $this->getDetails($xml, $title, $album, $images, $tracks);
                }
            }
        }

        return array($terms, $album, $images, $tracks);
    }


    public function searchbyAlbumAndTitle($albumName, $title)
    {
        $search = new Search();
        $search
            ->setCategory('MP3Downloads')// DigitalMusic, MusicTracks, Music, MP3Downloads
            ->setCondition('All')// New (default) | Used | Collectible | Refurbished | All
            ->setRelationshipType('Tracks')
            ->setResponseGroup(array(
                'RelatedItems',
                'ItemAttributes',
                'Images',
            ))// Tracks won't work here
            ->setTitle($title)
            ->setKeywords(sprintf("%s %s", $albumName, $title));
        $xmlResponse = $this->apaiIO->runOperation($search);
        $xml         = new \SimpleXMLElement($xmlResponse);

        return $xml;
    }

    /**
     * @param string $terms
     *
     * @return array
     */
    public function prepareItems($terms = "")
    {
        if (empty($terms) || !is_string($terms)) {
            throw new \InvalidArgumentException(sprintf("terms argument passed to %s must be a string and cannot be null", __METHOD__));
        }
        $items = explode(" - ", $terms);
        $artist = $title = $albumName = null;

        $excluding = array();
        for ($i = 1900; $i <= 2050; $i++) {
            $excluding[] = $i;
        }
        $excluding = array_merge($excluding,
            array('Album', 'inconnu', "[COVER]"));

        if (isset($items[0])) {
            $artist = explode("(", $items[0]);
            $artist = trim(array_shift($artist));
            $artist = trim(str_replace($excluding, "", $artist));
        }

        if (isset($items[1])) {
            $title = explode("(", $items[1]);
            $title = array_shift($title);
            $title = trim(str_replace($excluding, "", $title));
        }

        if (isset($items[2])) {
            $albumName = explode("(", $items[2]);
            $albumName = array_shift($albumName);
            $albumName = trim(str_replace($excluding, "", $albumName));
        }

        return array($artist, $title, $albumName);
    }

    /**
     * @param string  $artist
     * @param string  $albumName
     * @param boolean $titlePrecision
     *
     * @return \SimpleXMLElement
     *
     * @throws \Exception
     */
    public function tracksSearch($artist = "", $albumName = "", $titlePrecision = false)
    {
        if (empty($artist)) {
            throw new \InvalidArgumentException(sprintf("artist argument passed to %s must be a string and cannot be null", __METHOD__));
        }
        if (empty($albumName)) {
            throw new \InvalidArgumentException(sprintf("albumName argument passed to %s must be a string and cannot be null", __METHOD__));
        }
        $search = new Search();
        $search
            ->setCategory('Music')// DigitalMusic, MusicTracks, Music, MP3Downloads
            ->setCondition('All')// New (default) | Used | Collectible | Refurbished | All
            ->setRelationshipType('Tracks')
            ->setResponseGroup(array('ItemAttributes', 'Tracks', 'Images'))
            //->setArtist($artist) // works better with keywords
            ->setKeywords(sprintf("%s %s", $artist, $albumName)); // title value makes search fail more often
            if ($titlePrecision) {
                $search->setTitle($albumName);
            }
        ;
        $xmlResponse = $this->apaiIO->runOperation($search);

        return new \SimpleXMLElement($xmlResponse);
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param string            $title
     * @param \SimpleXMLElement $album
     * @param \SimpleXMLElement $images
     * @param \SimpleXMLElement $tracks
     *
     * @return array
     */
    public function getDetails($xml, $title = null, $album = null, $images = null, $tracks = null)
    {
        if (!$album && isset($xml->Items->Item->ItemAttributes)) {
            $album = $xml->Items->Item->ItemAttributes;
        }

        if (!$images && isset($xml->Items->Item->ImageSets)) {
            $images = $xml->Items->Item->ImageSets;
        }
        if (!$tracks) {
            $tracks = $this->findTrackListInDiscography($xml, $title);
        }

        if (!$tracks && isset($xml->Items->Item->Tracks->Disc)) {
            $tracks = $xml->Items->Item->Tracks->Disc->children();
        }

        return array($album, $images, $tracks);
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param string            $title
     *
     * @return array|null
     */
    protected function findTrackListInDiscography($xml, $title)
    {
        $namespaces = $xml->getNamespaces();
        $namespace = array_pop($namespaces);
        $xml->registerXPathNamespace("x", $namespace);
        $discography = $xml->xpath("x:Items/x:Item/x:Tracks/x:Disc");
        $discography  = json_decode(json_encode($discography));
        $result = null;
        foreach ($discography as $disc) {
            $titles = $disc->Track;
            if (is_array($titles) && !empty($titles)) {
                $TITLES = str_replace(array(" "), "", array_map("strtoupper", $titles));
                if (in_array(strtoupper(str_replace(array(" "), "", trim($title))), $TITLES)) {
                    $result = $titles;
                }
            }
        }

        return $result;
    }

    /**
     * @param string $ASIN
     *
     * @return \SimpleXMLElement
     *
     * @throws \Exception
     */
    public function findAlbumDetail($ASIN)
    {
        $search = new Lookup();
        $search
            ->setCondition('All')// New (default) | Used | Collectible | Refurbished | All
            ->setItemId($ASIN)
            ->setIdType("ASIN")
            ->setRelationshipType('Tracks')
            ->setResponseGroup(array(
                'RelatedItems',
                'Small',
            ))
        ;
        $xmlResponse = $this->apaiIO->runOperation($search);
        $xml         = new \SimpleXMLElement($xmlResponse);

        return $xml;
    }
}