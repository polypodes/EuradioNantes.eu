<?php
namespace RadioSolution\ProgramBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class EmissionThemeAdmin extends Admin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('name', null, array('label' => 'Nom'))
      ->add('description', null, array('label' => 'Description'))
      ->add('type', 'sonata_type_model', array('label' => 'Couleur du thème'))
    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('name')
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('name')
    ;
  }

  public function validate(ErrorElement $errorElement, $object)
  {
    $errorElement
      ->with('name')
      ->assertMaxLength(array('limit' => 32))
      ->end()
    ;
  }
}
