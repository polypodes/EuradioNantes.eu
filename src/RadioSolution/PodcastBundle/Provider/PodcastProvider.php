<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RadioSolution\PodcastBundle\Provider;

use Gaufrette\Filesystem;
use Imagine\Image\ImagineInterface;
use Sonata\CoreBundle\Model\Metadata;
use Sonata\MediaBundle\CDN\CDNInterface;
use Sonata\MediaBundle\Generator\GeneratorInterface;
use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Thumbnail\ThumbnailInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;
use Sonata\MediaBundle\Provider\FileProvider;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class PodcastProvider extends FileProvider
{
    protected $iconPath = '/bundles/podcast/images/mp34.png';

    /**
     * @param string                                                $name
     * @param \Gaufrette\Filesystem                                 $filesystem
     * @param \Sonata\MediaBundle\CDN\CDNInterface                  $cdn
     * @param \Sonata\MediaBundle\Generator\GeneratorInterface      $pathGenerator
     * @param \Sonata\MediaBundle\Thumbnail\ThumbnailInterface      $thumbnail
     * @param array                                                 $allowedExtensions
     * @param array                                                 $allowedMimeTypes
     * @param \Sonata\MediaBundle\Metadata\MetadataBuilderInterface $metadata
     */
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail, MetadataBuilderInterface $metadata = null)
    {
        parent::__construct($name, $filesystem, $cdn, $pathGenerator, $thumbnail, array('mp3'), array('audio/mp3', 'audio/mpeg', /*'application/octet-stream'*/), $metadata);
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderMetadata()
    {
        return new Metadata($this->getName(), $this->getName().'.description', false, 'SonataMediaBundle', array('class' => 'fa fa-file-sound-o'));
    }

     /**
     * {@inheritdoc}
     */
    public function buildCreateForm(FormMapper $formMapper)
    {
        $formMapper->add('binaryContent', 'file', array(
            'constraints' => array(
                new NotBlank(),
                new NotNull(),
            ),
        ));
        $formMapper->add('name', null, array('required' => false));
        $formMapper->add('enabled', null, array('required' => false));
        $formMapper->add('authorName', null, array('required' => false));
        $formMapper->add('cdnIsFlushable', null, array('required' => false));
        $formMapper->add('description', null, array('required' => false));
        $formMapper->add('copyright', null, array('required' => false));
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper)
    {
        $formMapper->add('name');
        $formMapper->add('enabled', null, array('required' => false));
        $formMapper->add('authorName');
        $formMapper->add('cdnIsFlushable');
        $formMapper->add('description');
        $formMapper->add('copyright');
    }

    /**
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     *
     * @return string
     */
    protected function generateReferenceName(MediaInterface $media)
    {
        if ($media->getBinaryContent() instanceof UploadedFile) {
            return $media->getBinaryContent()->getClientOriginalName();
        } elseif ($media->getBinaryContent() instanceof File) {
            return $media->getBinaryContent()->getFilename();
        }
        return 'error';
    }

    /**
     * {@inheritdoc}
     */
    protected function doTransform(MediaInterface $media)
    {
        parent::doTransform($media);

    //    if ($media->getBinaryContent() instanceof UploadedFile) {
    //        $fileName = $media->getBinaryContent()->getClientOriginalName();
    //    } elseif ($media->getBinaryContent() instanceof File) {
    //        $fileName = $media->getBinaryContent()->getFilename();
    //    } else {
    //        // Should not happen, FileProvider should throw an exception in that case
    //        return;
    //    }
    //    if (!in_array(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)), $this->allowedExtensions)
    //        || !in_array($media->getBinaryContent()->getClientMimeType(), $this->allowedMimeTypes)) {
    //        die(var_dump($fileName, $this->allowedExtensions, $this->allowedMimeTypes));
    //        return;
    //    }

    //    // this is the name used to store the file
    //    if (!$media->getProviderReference() ||
    //        $media->getProviderReference() === MediaInterface::MISSING_BINARY_REFERENCE
    //    ) {
    //        $media->setProviderReference($this->generateReferenceName($media));
    //    }

        if ($media->getBinaryContent()) {
            $media->setContentType($media->getBinaryContent()->getClientMimeType());
            $media->setSize($media->getBinaryContent()->getSize());
        }

        //$media->setProviderReference($media->getName());
        //$media->setContentType($media->getBinaryContent()->getClientMimeType());

        //die(var_dump($media));
    }

    /**
     * {@inheritdoc}
     */
    //public function updateMetadata(MediaInterface $media, $force = true)
    //{
    //    try {
    //        if (!$media->getBinaryContent() instanceof \SplFileInfo) {
    //            // this is now optimized at all!!!
    //            $path       = tempnam(sys_get_temp_dir(), 'sonata_update_metadata'); die(var_dump($path));
    //            $fileObject = new \SplFileObject($path, 'w');
    //            $fileObject->fwrite($this->getReferenceImage($media)->getContent());
    //        } else {
    //            $fileObject = $media->getBinaryContent();
    //        }
//
    //        $media->setSize($fileObject->getSize());
//
    //    } catch (\LogicException $e) {
    //        $media->setProviderStatus(MediaInterface::STATUS_ERROR);
    //        $media->setSize(0);
    //    }
    //}

    /**
     * {@inheritdoc}
     */
    public function generatePublicUrl(MediaInterface $media, $format)
    {
        if ($format == 'reference') {
            $path = $this->getReferenceImage($media);
        } else {
            //$path = $this->thumbnail->generatePublicUrl($this, $media, $format);
            return $this->iconPath;
        }

        return $this->getCdn()->getPath($path, $media->getCdnIsFlushable());
    }

    /**
     * {@inheritdoc}
     */
    public function generatePrivateUrl(MediaInterface $media, $format)
    {
        if ($format == 'reference') {
            return $this->getReferenceImage($media);
        } /*else {
            return $this->iconPath; //$this->thumbnail->generatePublicUrl($this, $media, $format);
        }*/

        return false;
    }

    /**
     * Set media binary content according to request content.
     *
     * @param MediaInterface $media
     */
    protected function generateBinaryFromRequest(MediaInterface $media)
    {
        if (php_sapi_name() === 'cli') {
            throw new \RuntimeException('The current process cannot be executed in cli environment');
        }

        $request = $media->getBinaryContent();
        if (!$request instanceof Request) {
            throw new \RuntimeException('Expected Request in binary content');
        }

        if (!$request->getClientMimeType()) {
            throw new \RuntimeException(
                'You must provide the content type value for your media before setting the binary content'
            );
        }

        $content = $request->getContent();

        // create unique id for media reference
        $guesser = ExtensionGuesser::getInstance();
        $extension = $guesser->guess($request->getClientMimeType());

        if (!$extension) {
            throw new \RuntimeException(
                sprintf('Unable to guess extension for content type %s', $request->getClientMimeType())
            );
        }

        $handle = tmpfile();
        fwrite($handle, $content);
        $file = new ApiMediaFile($handle);
        $file->setExtension($extension);
        $file->setMimetype($request->getClientMimeType());

        $media->setBinaryContent($file);
    }
}
