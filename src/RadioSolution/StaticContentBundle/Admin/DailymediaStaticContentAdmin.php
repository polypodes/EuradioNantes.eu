<?php
namespace RadioSolution\StaticContentBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class DailymediaStaticContentAdmin extends Admin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('title', null, array('label' => 'Titre'))
      ->add('link', null, array('label' => 'Lien'))
      ->add('author', null, array('label' => 'Auteur'))
      ->add('date', null, array('label' => 'Date'))
      ->add('image', 'sonata_type_model_list', array('label' => 'Image'), array('link_parameters' => array('provider' => 'sonata.media.provider.image')))
    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('title', null, array('label' => 'Titre'))
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('title', null, array('label' => 'Titre'))
    ;
  }

  public function validate(ErrorElement $errorElement, $object)
  {
    $errorElement
      ->with('title')
      ->assertLength(array('max' => 32))
      ->end()
    ;
  }
}
