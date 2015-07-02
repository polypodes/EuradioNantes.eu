<?php
namespace RadioSolution\CarrouselBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class CustomItemAdmin extends Admin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('title', null, array('label' => 'Titre'))
      ->add('url', null, array('label' => 'URL'))
      ->add('image', 'sonata_type_model_list', array('required' => false))
    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('title', null, array('label' => 'Titre'))
      ->add('url', null, array('label' => 'URL'))
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id', null, array('label' => 'Identifiant'))
      ->add('title', null, array('label' => 'Titre'))
      ->add('url', null, array('label' => 'URL'))
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
