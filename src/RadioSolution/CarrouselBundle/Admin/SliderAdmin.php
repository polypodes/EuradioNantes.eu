<?php
namespace RadioSolution\CarrouselBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class SliderAdmin extends Admin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('title', null, array('label' => 'Titre'))
    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('title', null, array('label' => 'Titre'))
      ->add('item', null, array('label' => 'Élément'))
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id', null, array('label' => 'Identifiant'))
      ->add('title', null, array('label' => 'Titre'))
      ->add('item', null, array('label' => 'Élément'))
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
      ->with('title')
      ->assertMaxLength(array('limit' => 32))
      ->end()
    ;
  }
}
