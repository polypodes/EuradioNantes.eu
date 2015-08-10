<?php
namespace RadioSolution\MenuBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class ItemAdmin extends Admin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('name', null, array('label' => 'Nom'))
      ->add('url', null, array('label' => 'URL'))

      ->add('menu', 'sonata_type_model', array('label' => 'Menu'))
      ->add('order_item', null, array('label' => 'Ordre dans le menu'))

      ->add('parent', 'sonata_type_model', array('label' => 'Entrée parente', 'required' => false))
    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('menu', null, array('label' => 'Menu'))
      ->add('parent', null, array('label' => 'Parent'))
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('name', null, array('label' => 'Nom'))
      ->add('menu', null, array('label' => 'Menu'))
      ->add('parent', null, array('label' => 'Parent'))
      ->add('order_item', null, array('label' => 'Ordre'))
      ->add('_action', 'actions', array(
      		'actions' => array(
      				'view' => array(),
      				'edit' => array(),
      		)
      ))
    ;
  }

  public function validate(ErrorElement $errorElement, $object)
  {
    $errorElement
      ->with('name')
      ->assertLength(array('max' => 32))
      ->end()
    ;
  }
}
