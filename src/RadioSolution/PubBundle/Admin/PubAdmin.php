<?php
namespace RadioSolution\PubBundle\Admin;

use RadioSolution\PubBundle\Entity\Pub;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class PubAdmin extends Admin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('name', null, array('label' => 'Nom'))
      ->add('enable', null, array('label' => 'Activé'))
      ->add('order_pub', 'integer', array('label' => 'Ordre', 'required'=>true))
      ->add('link', null, array('label' => 'Lien'))
      ->add('image', 'sonata_type_model_list', array('label' => 'Image'), array('link_parameters' => array('provider' => 'sonata.media.provider.image')))
    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('name', null, array('label' => 'Nom'))
      ->add('enable', null, array('label' => 'Activé'))
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('name', null, array('label' => 'Nom'))
      ->add('enable', null, array('label' => 'Activé'))
      ->add('order_pub', null, array('label' => 'Ordre'))
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
