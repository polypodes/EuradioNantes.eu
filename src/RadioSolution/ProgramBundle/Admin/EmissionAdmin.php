<?php
namespace RadioSolution\ProgramBundle\Admin;

use RadioSolution\ProgramBundle\Entity\Program;
use RadioSolution\ProgramBundle\Entity\Emission;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use RadioSolution\ProgramBundle\Entity\ExceptionalBroadcast;
use RadioSolution\ProgramBundle\Entity\WeeklyBroadcast;


class EmissionAdmin extends Admin
{
  protected $datagridValues = array(
    '_sort_order' => 'ASC', // sort direction
    '_sort_by' => 'name' // field name
  );

  protected function configureFormFields(FormMapper $formMapper)
  {

    $formMapper
      ->with('Général')
        ->add('published', null, array('label' => 'Publié'))
        ->add('name', null, array('label' => 'Nom'))
        ->add('description', 'ckeditor', array('config_name' => 'plus'))
        //->add('theme', 'sonata_type_model', array('label' => 'Thème', 'required' => false))
        ->add('collection', 'sonata_type_model_list', array('required' => true, 'btn_add' => false))
        //->add('collection', 'sonata_type_model', array('label' => 'Catégorie', 'required' => true), array('context' => 'emission')) // sonata_category_selector
        ->add('group', 'sonata_type_model', array('label' => 'Groupe', 'required' => false, 'btn_add' => false))
        ->add('media', 'sonata_type_model_list', array('label' => 'Média', 'required' => false), array('link_parameters' => array('provider'=>'sonata.media.provider.image')))
        ->add('archive', null, array('required' => false))
        ->add('frequency', 'sonata_type_model', array('label' => 'Fréquence', 'required' => false, 'btn_add' => false))
      ->end()
      ->with('Diffusions')
        ->add('diffusion_start','sonata_type_date_picker', array('label' => 'Date de début de diffusion'))
        ->add('diffusion_stop','sonata_type_date_picker', array('label' => 'Date d’arrêt de diffusion'))
        ->add('exceptionalBroadcasts', 'sonata_type_collection', array('label' => 'Diffusion exceptionnelle', 'required' => false, 'by_reference' => false), array(
          'edit' => 'inline',
          'inline' => 'table',
        ))
        ->add('weeklyBroadcasts', 'sonata_type_collection', array('label' => 'Diffusion hebdomadaire', 'required' => false, 'by_reference' => false), array(
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
      ->add('published', null, array('label' => 'Publié'))
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
      ->add('published', 'boolean', array('label' => 'Publié', 'editable' => true))
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

  public function validate(ErrorElement $errorElement, $obj)
  {
    $errorElement
      //->with('name')
      //  ->assertLength(array('max' => 32))
      //->end()
      ->with('description')
        ->assertNotNull()
      ->end()
    ;
  }

  public function setPrograms($object)
  {
    $timeStampDay = 3600*24;
    $timeStampWeek = $timeStampDay*7;

    //$object->setDiffusionStart();

    $now = new \DateTime('tomorrow');
    $now->setTime('00','00');

    // purge previous future programs
    $q = $this->createQuery()
      ->delete('ProgramBundle:Program','p')
      ->where('p.emission = :id_emission AND p.time_start >= :now')
      ->setParameters(array(
        'id_emission' => (String) $object->getId(),
        'now' => $now)
      )
    ;
    $q->getQuery()->execute();

    // Exceptionnal broadcasts
    $exceptional = $object->getExceptionalBroadcasts();
    foreach ($exceptional as $value){
      if ($value->getTimeStart() > $now) {
        $value->setEmission($object);
        $program = new Program();
        $program->setTimeStart($value->getTimeStart());
        //$timeValue = $value->getTimeStart()->getTimestamp() + $value->getDuration()->getTimestamp();
        //$program->setTimeStop($newDate->setTimestamp($timeValue));
        $duration = $value->getDuration()->format('G\Hi\M');
        $timeStop = clone $program->getTimeStart();
        $timeStop->add(new \DateInterval('PT' . $duration));
        $program->setTimeStop($timeStop);
        $program->setEmission($object);

        $this->prePersist($program);
        $this->getModelManager()->create($program);
        $this->postPersist($program);
        $this->createObjectSecurity($program);
      }
    }

    // weekly broadcasts
    $weekly = $object->getWeeklyBroadcasts();
    /*foreach ($weekly as $value) {
      $timestamp = $object->getDiffusionStart()->getTimestamp() + $timeStampDay;
      $dateDay = date("N", $timestamp);

      while ($dateDay != $value->getDay()) {
        $timestamp += $timeStampDay;
        $dateDay = date("N", $timestamp);
      }
      for ($timestamp; $timestamp < $object->getDiffusionStop()->getTimestamp(); $timestamp += $timeStampWeek) {
        $value->setEmission($object);

        $program = new Program();
        $program->setTimeStart($newDate->setTimestamp($timestamp + $value->getHour()->getTimestamp()));
        $timeValue = $program->getTimeStart()->getTimestamp() + $value->getDuration()->getTimestamp();
        $program->setTimeStop($newDate2->setTimestamp($timeValue));
        $program->setEmission($object);

        $this->prePersist($program);
        $this->getModelManager()->create($program);
        $this->postPersist($program);
        $this->createObjectSecurity($program);
      }

      $value->setEmission($object);
    }*/

    $currentDate = clone $now;
    $stopDate = $object->getDiffusionStop();

    while($currentDate < $stopDate) {
      $dayNum = $currentDate->format('N');
      foreach ($weekly as $value) {
        if ($dayNum != $value->getDay()) continue;

        $program = new Program();
        $program->setEmission($object);

        $hour = $value->getHour()->format('G\Hi\M');
        $duration = $value->getDuration()->format('G\Hi\M');

        $progStart = clone $currentDate;
        $progStart->add(new \DateInterval('PT' . $hour));

        $progStop = clone $progStart;
        $progStop->add(new \DateInterval('PT' . $duration));

        $program->setTimeStart($progStart);
        $program->setTimeStop($progStop);

        $this->prePersist($program);
        $this->getModelManager()->create($program);
        $this->postPersist($program);
        $this->createObjectSecurity($program);
      }
      $currentDate->modify('+1 day');
    }

  }

  public function prePersist($obj)
  {
    //var_dump('prePersist');
    //var_dump($obj);
    //if ($obj instanceof Emission) {
    //  $obj->updatedTimestamps();
    //}
    //var_dump($this->getSubject()->getWeeklyBroadcast()->first());
    //exit;
  }

  public function preUpdate($obj)
  {
    //var_dump('preUpdate');
    //var_dump($obj);
//
    //if ($obj instanceof Emission) {
    //  $obj->updatedTimestamps();
    //}
    //var_dump($this->getSubject()->getWeeklyBroadcast()->first());
    //exit;
  }

  public function postPersist($obj)
  {
    //var_dump('postPersist');
    if ($obj instanceof Emission) {
      $this->setPrograms($obj);
    }
  }

  public function postUpdate($obj)
  {
    //var_dump('postUpdate');
    if ($obj instanceof Emission) {
      $this->setPrograms($obj);
    }
  }

}
