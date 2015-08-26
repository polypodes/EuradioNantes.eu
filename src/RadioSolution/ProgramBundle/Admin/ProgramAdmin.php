<?php
namespace RadioSolution\ProgramBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class ProgramAdmin extends Admin
{

	protected $datagridValues = array(
			'_sort_order' => 'DESC', // sort direction
			'_sort_by' => 'time_start' // field name
	);

  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('time_start','sonata_type_datetime_picker', array('label' => 'Date de début'))
      ->add('time_stop','sonata_type_datetime_picker', array('label' => 'Date de fin'))
      ->add('podcast', 'sonata_type_model_list', array('label' => 'Podcast', 'required' => false), array())
      ->add('emission', 'sonata_type_model_list', array('label' => 'Émission', 'required' =>true), array())
    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('emission', null, array('label' => 'Émission'))
      ->add('time_start', null, array('label' => 'Date de début'))
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->add('emission', null, array('label' => 'Émission'))
      ->add('time_start', null, array('label' => 'Date de début'))
      ->add('time_stop', null, array('label' => 'Date de fin'))
      ->add('collision', null, array('label' => 'Collision'))
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
    	if ($object->getPodcast()){
    		$object->getPodcast()->setRealTimeStart($object->getTimeStart());
    		$object->getPodcast()->getPost()->setCreatedAt($object->getTimeStart());
    	}
  }
}
