<?php
namespace  RadioSolution\ProgramBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class DayAdmin extends Admin
{
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
		->add('time_start', null array('label' => 'Date de début'))
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
