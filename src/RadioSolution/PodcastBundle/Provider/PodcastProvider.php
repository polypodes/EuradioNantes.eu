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

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Sonata\MediaBundle\Thumbnail\FormatThumbnail;
use Symfony\Component\Finder\Finder;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\CDN\CDNInterface;
use Sonata\MediaBundle\Generator\GeneratorInterface;
use Sonata\MediaBundle\Thumbnail\ThumbnailInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Model\Metadata;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormBuilder;
use Gaufrette\Filesystem;
use Sonata\MediaBundle\Provider\BaseProvider;

// @todo revamp hardcoded pathes: uploads/ftp, /var/www/..., etc.
class PodcastProvider extends BaseProvider
{

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var array
     */
    protected $files;

    /**
     * @var array
     */
    protected $allowedExtensions;

    /**
     * @var array
     */
    protected $allowedMimeTypes;

    /**
     * @var string
     */
    protected $iconPath;

    /**
     * @var array
     */
    protected $metadata;


    /**
     * @param string             $name
     * @param Filesystem         $filesystem
     * @param CDNInterface       $cdn
     * @param GeneratorInterface $pathGenerator
     * @param ThumbnailInterface $thumbnail
     */
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail)
    {
        parent::__construct($name, $filesystem, $cdn, $pathGenerator, $thumbnail);

        $this->allowedExtensions = array('mp3','ogg');
        $this->allowedMimeTypes  = array('audio/mpeg','audio/mp3','audio/ogg');
        $this->iconPath= '/bundles/podcast/images/mp34.png';
        $this->metadata = array();
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderMetadata()
    {
        return new Metadata($this->getName(), $this->getName().'.description', false, 'RadioSolutionPodcastBundle', array('class' => 'fa fa-file-sound-o'));
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceImage(MediaInterface $media)
    {
        return sprintf('%s/%s',
            $this->generatePath($media),
            $media->getProviderReference()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceFile(MediaInterface $media)
    {
        return $this->getFilesystem()->get($this->getReferenceImage($media), true);
    }


    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper)
    {
        $list = array();
        $finder = new Finder();
        $finder->files()->name('/\.mp3$/')->in('uploads/ftp/');
        $finder->sortByName();
        /* @var $finder \SplFileInfo[] */
        foreach ($finder as $mp3) {
            //$filename = 'uploads/ftp/'.$mp3->getFilename();
            //$handle = fopen($filename, "rb");
            $list[$mp3->getFilename()] = $mp3->getFilename();
            $this->files[$mp3->getFilename()] = $mp3;
        }
        $list[] = ' ';
        $formMapper->add('name');
        $formMapper->add('enabled', null, array('required' => false));
        $formMapper->add('authorName');
        $formMapper->add('cdnIsFlushable');
        $formMapper->add('description');
        $formMapper->add('copyright');
        $formMapper->add('binaryContent', 'choice', array('choices' => $list));
    }

    /**
     * {@inheritdoc}
     */
    public function buildCreateForm(FormMapper $formMapper)
    {
        $list = array();
        $finder = new Finder();
        $finder->files()->name('/\.mp3$/')->in('uploads/ftp/');
        /* @var $finder \SplFileInfo[] */
        foreach ($finder as $mp3) {
            //$filename = 'uploads/ftp/'.$mp3->getFilename();
            //$handle = fopen($filename, "rb");
            $list[$mp3->getFilename()] = $mp3->getFilename();
            $this->files[$mp3->getFilename()] = $mp3;
        }
        $list[] = ' ';
        $formMapper->add('binaryContent', 'choice', array('choices' => $list));
    }

    /**
     * {@inheritdoc}
     */
    public function buildMediaType(FormBuilder $formBuilder)
    {
        $finder = new Finder();
        $finder->files()->name('/\.mp3$/')->in('uploads/ftp/');
        /* @var $finder \SplFileInfo[] */
        foreach ($finder as $mp3) {
            //$filename = 'uploads/ftp/'.$mp3->getFilename();
            //$handle = fopen($filename, "rb");
            $list[$mp3->getFilename()] = $mp3->getFilename();
            $this->files[$mp3->getFilename()] = $mp3;
            unset($handle);
        }
        $list[] = ' ';
        $formBuilder->add('binaryContent', 'choice', array('choices' => $list));
    }

    /**
     * {@inheritdoc}
     */
    public function postPersist(MediaInterface $media)
    {
        if ($media->getBinaryContent() === null) {
            return;
        }
        $this->setFileContents($media);
        $this->generateThumbnails($media);
        $this->mp32OggFile($media);
        # @todo Dude, please explain me that shitty condition:
        if (strstr($this->fileName, 'uploads/ftp')) {
            unlink($this->fileName);
        } else {
            //die(var_dump(__DIR__));
            //unlink($this->fileName);
        }

        $media->resetBinaryContent();
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate(MediaInterface $media)
    {
        if (!$media->getBinaryContent() instanceof \SplFileInfo) {
            return;
        }

        // Delete the current file from the FS
        $oldMedia = clone $media;
        // if no previous reference is provided, it prevents
        // Filesystem from trying to remove a directory
        // @todo: setBinaryContent should have set media::previousProviderReference, but it didn't
        if ($media->getPreviousProviderReference() !== null) {
            $oldMedia->setProviderReference($media->getPreviousProviderReference());
            $path = $this->getReferenceImage($oldMedia);

            if ($this->getFilesystem()->has($path)) {
                $this->getFilesystem()->delete($path);
            }
        }

        $this->fixBinaryContent($media);

        $this->setFileContents($media);

        $this->generateThumbnails($media);

        $media->resetBinaryContent();
    }

    /**
     * @throws \RuntimeException
     *
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     */
    protected function fixBinaryContent(MediaInterface $media)
    {
        if ($media->getBinaryContent() === null || $media->getBinaryContent() instanceof File) {
            return;
        }

        if ($media->getBinaryContent() instanceof Request) {
            $this->generateBinaryFromRequest($media);
            $this->updateMetadata($media);

            return;
        }


        // if the binary content is a filename => convert to a valid File
        if (!$media->getBinaryContent() instanceof File) {
            $this->fileName=$media->getBinaryContent();
            if (isset($this->files[$media->getBinaryContent()])) {
                $media->setBinaryContent($this->files[$media->getBinaryContent()]);
            }
            if (!is_file($media->getBinaryContent())) {
                throw new \RuntimeException('The file does not exist : ' . $media->getBinaryContent());
            }
        }
        $binaryContent = new File($media->getBinaryContent());
        $media->setBinaryContent($binaryContent);

    }

    /**
     * @throws \RuntimeException
     *
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     */
    protected function fixFilename(MediaInterface $media)
    {
        if (!($media->getBinaryContent() instanceof UploadedFile)
            && !($media->getBinaryContent() instanceof File)) {
            throw new \InvalidArgumentException("No podcast found, please retry with a valid file.");
        }

        if ($media->getBinaryContent() instanceof UploadedFile) {
            $media->setName($media->getName() ?: $media->getBinaryContent()->getClientOriginalName());
            $media->setMetadataValue('filename', $media->getBinaryContent()->getClientOriginalName());
        } elseif ($media->getBinaryContent() instanceof File) {
            $media->setName($media->getName() ?: $media->getBinaryContent()->getBasename());
            $media->setMetadataValue('filename', $media->getBinaryContent()->getBasename());
        }


        if (!$media->getName()) {
            throw new \RuntimeException('Please define a valid media\'s name');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doTransform(MediaInterface $media)
    {

        $this->fixBinaryContent($media);
        $this->fixFilename($media);

        // this is the name used to store the file
        if (!$media->getProviderReference() ||
            $media->getProviderReference() === MediaInterface::MISSING_BINARY_REFERENCE
        ) {
            $media->setProviderReference($this->generateReferenceName($media));
        }

        if ($media->getBinaryContent()) {
            $media->setContentType($media->getBinaryContent()->getMimeType());
            $media->setSize($media->getBinaryContent()->getSize());
        }

        $media->setProviderStatus(MediaInterface::STATUS_OK);
    }

    /**
     * {@inheritdoc}
     */
    public function updateMetadata(MediaInterface $media, $force = true)
    {
        if (!$media->getBinaryContent() instanceof \SplFileInfo) {
            // this is now optimized at all!!!
            $path       = tempnam(sys_get_temp_dir(), 'sonata_update_metadata_');
            $fileObject = new \SplFileObject($path, 'w');
            $fileObject->fwrite($this->getReferenceFile($media)->getContent());
        } else {
            $fileObject = $media->getBinaryContent();
        }

        $media->setSize($fileObject->getSize());
    }

    /**
     * {@inheritdoc}
     */
    public function generatePublicUrl(MediaInterface $media, $format)
    {
        if ($format == 'reference') {
            $path = $this->getReferenceImage($media);
        } else {
            return $this->iconPath;
        }

        return $this->getCdn()->getPath($path, $media->getCdnIsFlushable());
    }

    /**
     * {@inheritdoc}
     */
    public function getHelperProperties(MediaInterface $media, $format, $options = array())
    {
        return array_merge(array(
            'title'       => $media->getName(),
            'thumbnail'   => $this->getReferenceImage($media),
            'file'        => $this->getReferenceImage($media),
        ), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function generatePrivateUrl(MediaInterface $media, $format)
    {
        if ($format == 'reference') {
            return $this->getReferenceImage($media);
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function preRemove(MediaInterface $media)
    {
        // never delete icon image
    }

    /**
     * Set the file contents for an image.
     *
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     * @param string                                   $contents path to contents, defaults to MediaInterface BinaryContent
     */
    protected function setFileContents(MediaInterface $media, $contents = null)
    {
        $file = $this->getFilesystem()->get(sprintf('%s/%s', $this->generatePath($media), $media->getProviderReference()), true);
        $metadata = $this->metadata ? $this->metadata->get($media, $file->getName()) : array();

        if ($contents) {
            $file->setContent($contents, $metadata);

            return;
        }

        if ($media->getBinaryContent() instanceof File) {
            $file->setContent(file_get_contents($media->getBinaryContent()->getRealPath()), $metadata);

            return;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function postRemove(MediaInterface $media)
    {
        // never delete icon image
    }

    /**
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     *
     * @return string
     */
    protected function generateReferenceName(MediaInterface $media)
    {
        return $this->generateMediaUniqId($media).'.'.$media->getBinaryContent()->guessExtension();
    }

    /**
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     *
     * @return string
     */
    protected function generateMediaUniqId(MediaInterface $media)
    {
        return sha1($media->getName().uniqid().rand(11111, 99999));
    }

    /**
     * {@inheritdoc}
     */
    public function getDownloadResponse(MediaInterface $media, $format, $mode = null, array $headers = array())
    {
        $headers = array_merge(array(
            'Content-Type'          => $media->getContentType(),
            'Content-Disposition'   => sprintf('attachment; filename="%s"', $media->getMetadataValue('filename')),
        ), $headers);

        // create default variables
        if ($mode == 'X-Sendfile') {
            $headers['X-Sendfile'] = $this->generatePrivateUrl($media, $format);
            $content = '';
        } elseif ($mode == 'X-Accel-Redirect') {
            $headers['X-Accel-Redirect'] = $this->generatePrivateUrl($media, $format);
            $content = '';
        } elseif ($mode == 'http') {
            $content = $this->getReferenceFile($media)->getContent();
        } else {
            $content = '';
        }

        return new Response($content, 200, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function validate(ErrorElement $errorElement, MediaInterface $media)
    {
        if (!$media->getBinaryContent() instanceof \SplFileInfo) {
            return;
        }

        if ($media->getBinaryContent() instanceof UploadedFile) {
            $fileName = $media->getBinaryContent()->getClientOriginalName();
        } elseif ($media->getBinaryContent() instanceof File) {
            $fileName = $media->getBinaryContent()->getFilename();
        } else {
            throw new \RuntimeException(sprintf('Invalid binary content type: %s', get_class($media->getBinaryContent())));
        }

        if (!in_array(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)), $this->allowedExtensions)) {
            $errorElement
                ->with('binaryContent')
                    ->addViolation('Invalid extensions')
                ->end();
        }

        if (!in_array($media->getBinaryContent()->getMimeType(), $this->allowedMimeTypes)) {
            $errorElement
                ->with('binaryContent')
                    ->addViolation('Invalid mime type : %type%', array('%type%' => $media->getBinaryContent()->getMimeType()))
                ->end();
        }
    }

    /**
     * Note: to install ffmpeg w/ libvorbis on Mac OSX:
     * > $ brew install ffmpeg --with-fdk-aac --with-ffplay --with-freetype \
     *   --with-libass --with-libquvi --with-libvorbis --with-libvpx --with-opus --with-x265
     * @link https://trac.ffmpeg.org/wiki/CompilationGuide
     * @link https://trac.ffmpeg.org/wiki/PHP
     *
*@param MediaInterface $file
     * @param bool|FALSE     $delete
     */
    protected function mp32OggFile(MediaInterface $file, $delete = false)
    {
        $path = $this->pathGenerator->generatePath($file);
        $fileName = $file->getProviderReference();
        $mediaPath = realpath(sprintf(__DIR__. "/../../../../web/uploads/media/%s/%s", $path, $fileName));
        if (file_exists($mediaPath)) {
            $_ENV['PATH'] = empty($_ENV['PATH']) ? "" : $_ENV['PATH'];
            putenv("PATH=" .$_ENV["PATH"]. ':/usr/local/bin:/usr/bin:/bin'); // You know, OS X devs using Brew, etc.
            $command = sprintf("ffmpeg -y -i %s", $mediaPath); // -y overwrite
            $command .= " -vcodec libtheora -acodec libvorbis ";
            $command .= sprintf("%s.ogg", $mediaPath);
            // https://trac.ffmpeg.org/wiki/PHP#Runningffmpeginthebackground:
            $command .= "  </dev/null >/dev/null 2>/tmp/ffmpeg.log &";
            $res = @system($command);
            if ($delete == TRUE) {
                //unlink($file); //cannot explain that.
            }
        } else {
            throw new \InvalidArgumentException("Unable to convert Podcast file in $mediaPath");
        }
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

        if (!$media->getContentType()) {
            throw new \RuntimeException(
                'You must provide the content type value for your media before setting the binary content'
            );
        }

        $request = $media->getBinaryContent();

        if (!$request instanceof Request) {
            throw new \RuntimeException('Expected Request in binary content');
        }

        $content = $request->getContent();

        // create unique id for media reference
        $guesser = ExtensionGuesser::getInstance();
        $extension = $guesser->guess($media->getContentType());

        if (!$extension) {
            throw new \RuntimeException(
                sprintf('Unable to guess extension for content type %s', $media->getContentType())
            );
        }

        $handle = tmpfile();
        fwrite($handle, $content);
        $file = new ApiMediaFile($handle);
        $file->setExtension($extension);
        $file->setMimetype($media->getContentType());

        $media->setBinaryContent($file);
    }
}
