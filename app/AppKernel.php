<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new AppBundle\AppBundle(),

            // Sonata
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\IntlBundle\SonataIntlBundle(),
            new Sonata\MediaBundle\SonataMediaBundle(),
            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\SeoBundle\SonataSeoBundle(),
            new Sonata\PageBundle\SonataPageBundle(),
            new Sonata\CacheBundle\SonataCacheBundle(),
            new Sonata\NotificationBundle\SonataNotificationBundle(),
            new Sonata\ClassificationBundle\SonataClassificationBundle(),
            new Sonata\NewsBundle\SonataNewsBundle(),
            new Sonata\DatagridBundle\SonataDatagridBundle(),
            new Sonata\FormatterBundle\SonataFormatterBundle(),
            new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
            // Sonata deps
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),
            # Overrided Sonata bundles
            new Application\Sonata\AdminBundle\ApplicationSonataAdminBundle(),
            new Application\Sonata\ClassificationBundle\ApplicationSonataClassificationBundle(),
            new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(), // -> overrided by CoopTilleulsCKEditorSonataMediaBundle
            new Application\Sonata\NewsBundle\ApplicationSonataNewsBundle(),
            new Application\Sonata\NotificationBundle\ApplicationSonataNotificationBundle(),
            new Application\Sonata\PageBundle\ApplicationSonataPageBundle(),
            new Application\Sonata\UserBundle\ApplicationSonataUserBundle(),
            new CoopTilleuls\Bundle\CKEditorSonataMediaBundle\CoopTilleulsCKEditorSonataMediaBundle(),

            // Misc
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new Application\Ivory\CKEditorBundle\ApplicationIvoryCKEditorBundle(),
            new EWZ\Bundle\RecaptchaBundle\EWZRecaptchaBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\CommentBundle\FOSCommentBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),


            // RadioSolution
            new RadioSolution\ProgramBundle\ProgramBundle(),
            new RadioSolution\PodcastBundle\PodcastBundle(),
            new RadioSolution\MenuBundle\MenuBundle(),
            new RadioSolution\StaticContentBundle\StaticContentBundle(),
            new RadioSolution\CarrouselBundle\CarrouselBundle(),
            new RadioSolution\ContactBundle\ContactBundle(),
            new RadioSolution\PubBundle\PubBundle(),
            new RadioSolution\SearchBundle\SearchBundle(),
            new RadioSolution\SurveyBundle\SurveyBundle(),
            new RadioSolution\RSSAgregatorBundle\RSSAgregatorBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
