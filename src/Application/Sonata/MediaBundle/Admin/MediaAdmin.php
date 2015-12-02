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
            ->add('providerName')
        ;
    }
}
