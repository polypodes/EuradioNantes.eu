<?php
namespace RadioSolution\ProgramBundle\Admin;

use RadioSolution\ProgramBundle\Entity\Emission;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class ExceptionalBroadcastAdmin extends Admin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      	->add('time_start','datetime', array('label' => 'Date de début')) //, 'data_timezone' => "GMT",'user_timezone' => "GMT"
      	->add('duration', null, array('label' => 'Durée (HH:mm)'))
    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('id')
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id')
    ;
  }

  public function validate(ErrorElement $errorElement, $object)
  {
  }
}
