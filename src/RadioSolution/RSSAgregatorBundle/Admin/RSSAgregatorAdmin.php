<?php
namespace RadioSolution\RSSAgregatorBundle\Admin;

use RadioSolution\RSSAgregatorBundle\Entity\RSSfile;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class RSSAgregatorAdmin extends Admin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('name', null, array('label' => 'Nom'))
      ->add('enable', null, array('label' => 'Activé'))
      ->add('url', null, array('label' => 'URL'))
      ->add('menu', 'sonata_type_model', array('label' => 'Menu'))
    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('name', null, array('label' => 'Nom'))
      ->add('enable', null, array('label' => 'Activé'))
      ->add('menu', null, array('label' => 'Menu'))
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('name', null, array('label' => 'Nom'))
      ->add('enable', null, array('label' => 'Activé'))
      ->add('menu', null, array('label' => 'Menu'))
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
