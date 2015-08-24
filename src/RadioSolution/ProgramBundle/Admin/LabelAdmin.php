<?php
namespace RadioSolution\ProgramBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class LabelAdmin extends Admin
{

  protected $datagridValues = array(
    '_sort_order' => 'DESC', // sort direction
    '_sort_by' => 'featuredFrom' // field name
  );

  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('published', null, array('label' => 'Publié'))
      ->add('featuredFrom', 'sonata_type_date_picker', array('label' => 'Label du mois du'))
      ->add('featuredTo', 'sonata_type_date_picker', array('label' => 'Au'))
      ->add('title', null, array('label' => 'Titre'))
      ->add('slug', null, array('label' => 'Titre URL', 'required' => false))
      ->add('resume', 'ckeditor', array('label' => 'Résumé', 'config_name' => 'mini'))
      ->add('content', 'ckeditor', array('label' => 'Contenu', 'config_name' => 'plus'))
      ->add('albums', 'sonata_type_model', array(
        'required' => false,
        'multiple' => true,
        'btn_add' => false,
        'btn_delete' => false,
        'label' => 'Albums',
        'by_reference' => false
      ))
      ->add('image', 'sonata_type_model_list', array('label' => 'Image'), array('link_parameters' => array('provider' => 'sonata.media.provider.image')))
    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('id')
      ->add('title')
      ->add('published')
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      //->addIdentifier('id')
      ->addIdentifier('title', 'string', array('label' => 'Titre'))
      ->add('featuredPeriod', 'string', array('label' => 'Label du mois'))
      ->add('published', 'boolean', array('label' => 'Publié', 'editable' => true))
      ->add('_action', 'actions', array(
        'actions' => array(
          'view' => array(),
          'edit' => array(),
        )
      ))
    ;
  }

  public function getBatchActions()
    {
        // retrieve the default batch actions (currently only delete)
        $actions = parent::getBatchActions();

        $actions['publish'] = array(
            'label' => $this->trans('Publier', array(), 'SonataAdminBundle'),
            'ask_confirmation' => true
        );
        $actions['unpublish'] = array(
            'label' => $this->trans('Dépublier', array(), 'SonataAdminBundle'),
            'ask_confirmation' => true
        );

        return $actions;
    }

  public function validate(ErrorElement $errorElement, $object)
  {

  }

  //public function prePersist($label)
  //{
  //    $this->preUpdate($label);
  //}

  public function preUpdate($label)
  {
    $label->preUpdate();
  }
}
