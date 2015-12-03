<?php
namespace Application\Sonata\MediaBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\MediaBundle\Provider\Pool;
use Sonata\MediaBundle\Form\DataTransformer\ProviderDataTransformer;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\MediaBundle\Admin\BaseMediaAdmin as BaseMediaAdmin;


class MediaAdmin extends BaseMediaAdmin
{

    /**
     * {@inheritdoc}
     */
    //protected function configureListFields(ListMapper $listMapper)
    //{
    //    $listMapper
    //        ->addIdentifier('name')
    //        ->add('description')
    //        ->add('enabled')
    //        ->add('size')
    //        ->add('_action', 'actions', array(
    //            'actions' => array(
    //                'view' => array(),
    //                'edit' => array(),
    //                'delete' => array(),
    //            )
    //        ))
    //    ;
    //}

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        //parent::configureDatagridFilters($datagridMapper);

        $datagridMapper
            ->add('name', null, array('label' => 'Nom'))
            ->add('providerName'/*, 'doctrine_orm_choice', array( //'doctrine_orm_choice' //'doctrine_orm_model_autocomplete'
                'label' => 'Type',
                //'property' => '',
                'choices' => array(
                    'sonata.media.provider.image' => 'image',
                    'sonata.media.provider.file' => 'document',
                    'sonata.media.provider.podcast' => 'podcast',
                    'sonata.media.provider.youtube' => 'Youtube',
                    'sonata.media.provider.dailymotion' => 'DailyMotion',
                )
            )*/)
            ->add('enabled', null, array('label' => 'Activé'))
        ;
    }
}
