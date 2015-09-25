<?php
namespace RadioSolution\ProgramBundle\Admin;

use RadioSolution\ProgramBundle\Entity\Program;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use RadioSolution\ProgramBundle\Entity\ExceptionalBroadcast;
use RadioSolution\ProgramBundle\Entity\WeeklyBroadcast;


class EmissionAdmin extends Admin
{
  protected function configureFormFields(FormMapper $formMapper)
  {

    $formMapper
      ->with('Général')
        ->add('name', null, array('label' => 'Nom'))
        ->add('description', 'ckeditor', array('config_name' => 'plus'))
        //->add('theme', 'sonata_type_model', array('label' => 'Thème', 'required' => false))
        ->add('collection', 'sonata_type_model_list', array('required' => true, 'btn_add' => false))
        //->add('collection', 'sonata_type_model', array('label' => 'Catégorie', 'required' => true), array('context' => 'emission')) // sonata_category_selector
        ->add('group', 'sonata_type_model', array('label' => 'Groupe', 'required' => false))
        ->add('media', 'sonata_type_model_list', array('label' => 'Média', 'required' => false), array('link_parameters' => array('provider'=>'sonata.media.provider.image')))
        ->add('archive', null, array('required' => false))
        ->add('frequency', 'sonata_type_model', array('label' => 'Fréquence', 'required' => false))
      ->end()
      ->with('Diffusions')
        ->add('diffusion_stop','sonata_type_date_picker', array('label' => 'Date d’arrêt de diffusion'))
        ->add('ExceptionalBroadcast', 'sonata_type_collection', array('label' => 'Diffusion exceptionnelle', 'required' => false, 'by_reference' =>true), array(
          'edit' => 'inline',
			    'inline' => 'table',
        ))
        ->add('WeeklyBroadcast', 'sonata_type_collection', array('label' => 'Diffusion hebdomadaire', 'required' => false, 'by_reference' =>true), array(
          'edit' => 'inline',
          'inline' => 'table',
        ))
      ->end();
    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('name', null, array('label' => 'Nom'))
      //->add('theme', null, array('label' => 'Thème'))
      ->add('collection', null, array('label' => 'Catégorie'))
      ->add('diffusion_stop', null, array('label' => 'Arrêt de la diffusion'))
      ->add('archive', null, array('label' => 'Archivé'))
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('name', null, array('label' => 'Nom'))
      //->add('theme', null, array('label' => 'Thème'))
      ->add('collection', null, array('label' => 'Catégorie'))
      ->add('diffusion_stop', null, array('label' => 'Arrêt de la diffusion'))
      ->add('archive', null, array('label' => 'Archivé'))
    ;
  }

  public function getBatchActions()
  {
      // retrieve the default batch actions (currently only delete)
      $actions = parent::getBatchActions();

      $actions['archive'] = array(
          'label' => $this->trans('Archiver', array(), 'SonataAdminBundle'),
          'ask_confirmation' => true
      );

      return $actions;
  }

  public function validate(ErrorElement $errorElement, $object)
  {
  	$timeStampDay=3600*24;
  	$timeStampWeek=$timeStampDay*7;

  	$object->setDiffusionStart();

  	$newDate=new \DateTime(null,new \DateTimeZone('GMT'));
  	$newDate2=new \DateTime(null,new \DateTimeZone('GMT'));
  	$now=new \DateTime('tomorrow');
  	$now->setTime('00','00');
  	$q=$this->createQuery()->delete('ProgramBundle:Program','p')
  	->where('p.emission = :id_emission AND p.time_start >= :now')
  	->setParameters(array('id_emission'=>(String)$object->getId(),'now'=>$now));

  	$q->getQuery()->execute();

  	$exceptional=$object->getExceptionalBroadcast();

  	foreach ($exceptional as $value){
  		if ($value->getTimeStart()->getTimestamp()>$now->getTimestamp()){
	  		$value->setEmission($object);
	  		$program=new Program();
	  		$program->setTimeStart($value->getTimeStart()->setTimezone(new \DateTimeZone('GMT')));
	  		$timeValue=$value->getTimeStart()->setTimezone(new \DateTimeZone('GMT'))->getTimestamp()+$value->getDuration()->setTimezone(new \DateTimeZone('GMT'))->getTimestamp();
	  		$program->setTimeStop($newDate->setTimestamp($timeValue));
	  		$program->setEmission($object);


	  		$this->prePersist($program);
	  		$this->getModelManager()->create($program);
	  		$this->postPersist($program);
	  		$this->createObjectSecurity($program);
  		}
  	}

  	$weekly=$object->getWeeklyBroadcast();

  	foreach ($weekly as $value){
  		$timestamp = $object->getDiffusionStart()->setTimezone(new \DateTimeZone('GMT'))->getTimestamp()+$timeStampDay;
  		$dateDay=date("N",$timestamp);
  		while($dateDay!=$value->getDay()){
  			$timestamp+=$timeStampDay;
  			$dateDay=date("N",$timestamp);
  		}
  		for($timestamp;$timestamp<$object->getDiffusionStop()->getTimestamp();$timestamp+=$timeStampWeek){
  			$value->setEmission($object);
  			$program=new Program();
  			$program->setTimeStart($newDate->setTimestamp($timestamp+$value->getHour()->setTimezone(new \DateTimeZone('GMT'))->getTimestamp()));
  			$timeValue=$program->getTimeStart()->setTimezone(new \DateTimeZone('GMT'))->getTimestamp()+$value->getDuration()->setTimezone(new \DateTimeZone('GMT'))->getTimestamp();
  			$program->setTimeStop($newDate2->setTimestamp($timeValue));
  			$program->setEmission($object);

  			$this->prePersist($program);
  			$this->getModelManager()->create($program);
  			$this->postPersist($program);
  			$this->createObjectSecurity($program);
  		}

  		$value->setEmission($object);
  	}

    $errorElement
      ->with('name')
      ->assertLength(array('max' => 32))
      ->end()
      ->with('description')
      ->assertNotNull()
      ->end()
    ;
  }

}
