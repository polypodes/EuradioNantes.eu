<?php
namespace RadioSolution\PodcastBundle\Admin;

use Application\Sonata\MediaBundle\Entity\Media;
use Sonata\UserBundle\Controller\AdminSecurityController;
use Imagine\Exception\Exception;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class PodcastAdmin extends Admin
{

	// setup the default sort column and order
	protected $datagridValues = array(
			'_sort_order' => 'DESC',
			'_sort_by' => 'real_time_start'
	);

  protected function configureFormFields(FormMapper $formMapper)
  {
    //$em = $this->modelManager->getEntityManager('RadioSolution\ProgramBundle\Entity\Program');
    //$query = $em->createQuery('SELECT p FROM ProgramBundle:Program p WHERE p.podcast IS NULL');
    //$results = $query->getResult();
    //$programs = array();
    //foreach ($results as $program) {
    //  $programs[$program->__toString()] = $program;
    //}
    //die(var_dump($programs));

    $formMapper
      ->with('Podcast', array(
            'class' => 'col-md-6'
        ))
        ->add('name', null, array('required' => true, 'label' => 'Nom du podcast'))
        ->add('home_page', null, array('required' => false, 'label' => 'Page d’accueil'))
        ->add('real_time_start', 'sonata_type_datetime_picker', array('required' => true, 'label' => 'Date de diffusion du podcast'))
        ->add('program', 'sonata_type_model_list', array('required' => true, 'label' => 'Programme associé', 'btn_add' => false))
        ->add('filePodcast', 'sonata_type_model_list', array('required' => true, 'label' => 'Media podcast', 'btn_list' => false), array('link_parameters' => array('provider'=>'sonata.media.provider.podcast')))
        ->add('dlAuth', null, array('required' => false, 'data' => true, 'label' => 'Autoriser le téléchargement ?'))
      ->end()

      ->with('Marqueurs temporels', array(
        'class' => 'col-md-6'
      ))
        ->add('player', 'podcast_player', array('label' => false, 'mapped' => false))
        ->add('markers', 'collection', array(
          'required' => false,
          'type' => 'podcast_marker',
          'label' => 'Marqueurs',
          'allow_add' => 'Ajouter un marqueur',
          'allow_delete' => true,
          'prototype' => true
        ))
      ->end()

      ->with('Article', array(
            'class' => 'col-md-12'
        ))
        ->add('post', 'sonata_type_admin', array('label' => false))
      ->end()


    ;
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('name')
      ->add('real_time_start')
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('name')
      ->add('real_time_start')
      ->add('filePodcast')
      ->add('dlAuth')
      ->add('home_page')
    ;
  }

  public function validate(ErrorElement $errorElement, $obj)
  {
  	$errorElement
    	->with('name')
    	 ->assertLength(array('max' => 32))
    	->end()
  	;

    if (empty($obj->getPost()->getSlug())) {
        $obj->getPost()->setSlug($obj->getPost()->getTitle());
    }
  }
}
