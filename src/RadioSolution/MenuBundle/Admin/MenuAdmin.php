<?php
namespace RadioSolution\MenuBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class MenuAdmin extends Admin
{
  //protected $datagridValues = array(
  //  '_sort_order' => 'ASC', // sort direction
  //  '_sort_by' => 'featuredFrom' // field name
  //);

  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('name', null, array('label' => 'Nom'))
    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('name', null, array('label' => 'Nom'))
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('name', null, array('label' => 'Nom'))
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
