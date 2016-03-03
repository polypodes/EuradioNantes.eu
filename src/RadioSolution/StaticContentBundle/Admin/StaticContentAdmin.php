<?php
namespace RadioSolution\StaticContentBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use RadioSolution\StaticContentBundle\Entity\StaticContent;

class StaticContentAdmin extends Admin
{

	// setup the default sort column and order
	protected $datagridValues = array(
			'_sort_order' => 'DESC',
			'_sort_by' => 'order_content'
	);

  protected function configureFormFields(FormMapper $formMapper)
  {
  	//$entities=$query->createQuery( 'SELECT c.order_content FROM StaticContentBundle:StaticContent c ORDER BY c.order_content ASC')->setMaxResults(1)->getResult();

	 $q = $this->createQuery('SELECT c FROM StaticContentBundle:StaticContent c ORDER BY c.order_content DESC');

   	$liste = $q->getQuery()->execute();
   	$maxValue = 0;

   	foreach ($liste as $result){
   		if (is_int($result->getOrderContent())) {
   			if ($result->getOrderContent()>$maxValue) {
   				$maxValue = $result->getOrderContent();
   			}
   		}
  	}
  	$label = 'Ordre du contenu (le plus grand '.$maxValue.')';
    $formMapper
      ->add('published', null, array('label' => 'Publié'))
      ->add('name', null, array('label' => 'Nom'))
      ->add('slug', null, array('label' => 'Titre URL', 'required' => false))
      ->add('categoryStaticContent', 'sonata_type_model', array('label' => 'Catégorie', 'required' => false))
      ->add('order_content', 'integer', array('label' => $label))
      ->add('introduction', 'ckeditor', array('label' => 'Introduction', 'required' => false, 'config_name' => 'mini'))
      ->add('body', 'ckeditor', array('label' => 'Contenu', 'config_name' => 'plus'))
      ->add('image', 'sonata_type_model_list', array('label' => 'Image'), array('link_parameters' => array('provider' => 'sonata.media.provider.image')))
    ;
    //$formMapper->setDefaults(array('order_content'=>$maxValue));
  }

  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('name', null, array('label' => 'Nom de la page'))
      ->add('categoryStaticContent', null, array('label' => 'Catégorie'))
    ;
  }

  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('name', null, array('label' => 'Nom'))
      //->add('order_content', null, array('label' => 'Ordre'))
      ->add('categoryStaticContent', null, array('label' => 'Catégorie'))
      ->add('URL', 'string', array('label' => 'URL', 'template' => 'StaticContentBundle:StaticContentAdmin:list_URL.html.twig'))
      ->add('published', null, array('label' => 'Publié', 'editable' => true))
    ;
  }

  public function validate(ErrorElement $errorElement, $object)
  {
    //$errorElement
    //  ->with('name')
    //  ->assertLength(array('max' => 32))
    //  ->end()
    //;
  }
}
