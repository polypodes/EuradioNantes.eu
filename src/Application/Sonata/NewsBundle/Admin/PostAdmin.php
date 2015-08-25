<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\NewsBundle\Admin;

use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\NewsBundle\Admin\PostAdmin as BaseAdmin;

class PostAdmin extends BaseAdmin
{

    protected $choices = array(
                    "actualite" => "actualité",
                    "podcast" => "podcast"
    );

    protected $datagridValues = array(
        '_sort_order' => 'DESC', // reverse order (default = 'ASC')
        '_sort_by' => 'publicationDateStart'  // name of the ordered field
    );

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $limit = 500; // limit => fastest loading, do we really want to link new post with OLD content ?

        $em = $this->modelManager->getEntityManager('Application\Sonata\NewsBundle\Entity\Post');
        $repo = $em->getRepository('Application\Sonata\NewsBundle\Entity\Post');

        //get related lists
        $podcasts= $repo->getPostByCategory("podcast");
        $news= $repo->getPostByCategory();
    
        parent::configureFormFields($formMapper);
        $formMapper
            ->with('Post', array(
                    'class' => 'col-md-8'
                ))
                ->add('short_title',"text", array('required' => false, 'label' => "Titre court"))
                ->add('abstract',"ckeditor", array())
                ->add('content',"ckeditor", array())
            ->end()
            
            ->with('Status', array(
                    'class' => 'col-md-4'
                ))
                ->add('commentsCloseAt', 'sonata_type_datetime_picker', array('required' => false, 'dp_side_by_side' => true))
                ->add('type',"choice", array('required' => false, 'label' => "Type","choices" => $this->choices))
            ->end()
            ->with('Classification', array(
                'class' => 'col-md-4'
                ))
                ->add('tags', 'sonata_type_model_autocomplete', array(
                    'property' => 'name',
                    'multiple' => 'true',
                    'required' => false
                ))
                ->add('slug',"text", array('required' => false, 'label' => "Slug"))
            ->end()
            ->with('Liaisons', array(
                'class' => 'col-md-4'
                ))
                ->add('related_news', 'sonata_type_model', array(
                    'required' => false,
                    'multiple' => true,
                    'label' => "Actualités",
                    "query" => $news
                ))
                ->add('related_podcasts', 'sonata_type_model', array(
                    'required' => false,
                    'multiple' => true,
                    'label' => "Podcasts",
                    "query" => $podcasts
                ))
            ->end()

            
        ;

    }

}
