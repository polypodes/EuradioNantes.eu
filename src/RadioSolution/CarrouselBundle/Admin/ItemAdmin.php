<?php
namespace RadioSolution\CarrouselBundle\Admin;

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
      ->add('order_int', null, array('label' => 'Ordre'))
      ->add('slider','sonata_type_model', array('label' => 'Carousel'))
      ->add('category_static_content', 'sonata_type_model_list', array('required' => false, 'label' => 'Catégorie'))
      ->add('static_content', 'sonata_type_model_list', array('required' => false, 'label' => 'Contenu statique'))
      ->add('emission', 'sonata_type_model_list', array('required' => false, 'label' => 'Émission'))
      ->add('podcast', 'sonata_type_model_list', array('required' => false, 'label' => 'Podcast'))
      ->add('custum_item', 'sonata_type_model_list', array('required' => false, 'label' => 'Élément personnalisé'))
    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('slider', null, array('label' => 'Carousel'))
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('order_int')
      ->add('slider', null, array('label' => 'Carousel'))
      ->add('category_static_content', null, array('label' => 'Catégorie'))
      ->add('static_content', null, array('label' => 'Contenu statique'))
      ->add('emission', null, array('label' => 'Émission'))
      ->add('podcast', null, array('label' => 'Podcast'))
      ->add('custom_item', null, array('label' => 'Élément personnalisé'))
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
  	if(!($object->getCategoryStaticContent() &&
  	   !$object->getStaticContent() &&
  	   !$object->getEmission() &&
  	   !$object->getPodcast() &&
       !$object->getCustumItem()
  	|| !$object->getCategoryStaticContent() &&
  	   $object->getStaticContent() &&
  	   !$object->getEmission() &&
  	   !$object->getPodcast() &&
       !$object->getCustumItem()
  	|| !$object->getCategoryStaticContent() &&
  	   !$object->getStaticContent() &&
  	   $object->getEmission() &&
  	   !$object->getPodcast() &&
       !$object->getCustumItem()
  	|| !$object->getCategoryStaticContent() &&
  	   !$object->getStaticContent() &&
  	   !$object->getEmission() &&
  	   $object->getPodcast() &&
       !$object->getCustumItem()
|| !$object->getCategoryStaticContent() &&
  	   !$object->getStaticContent() &&
  	   !$object->getEmission() &&
  	   !$object->getPodcast() &&
       $object->getCustumItem())){
  		$errorElement->with('value')->addViolation('un item doit contenir un et un seul champ de référence')->end();

  	}

  /*  $errorElement
      ->with('title')
      ->assertMaxLength(array('limit' => 32))
      ->end()*/
    ;
  }
}
