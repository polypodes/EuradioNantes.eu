<?php
namespace RadioSolution\ContactBundle\Admin;

//use Sonata\UserBundle\Controller\AdminSecurityController;
//use Imagine\Exception\Exception;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class ContactAdmin extends Admin
{

    // setup the default sort column and order
    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'position'
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array('label' => 'Titre du contact'))
            ->add('name', null, array('label' => 'Nom de la personne'))
            ->add('email', 'email', array('label' => 'Adresse e-mail'))
            ->add('position', null, array('label' => 'Ordre dans la liste'))
        ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
        ->add('title')
        ->add('name')
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('title', null, array('label' => 'Intitulé'))
      ->add('name', null, array('label' => 'Nom de la personne'))
      ->add('email', null, array('label' => 'Adresse e-mail'))
    ;
  }

  public function validate(ErrorElement $errorElement, $object)
  {
    $errorElement
        //->with('name')
        //    ->assertLength(array('max' => 32))
        //->end()
    ;
  }
}
