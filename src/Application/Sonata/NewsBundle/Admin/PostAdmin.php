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
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

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

        $em = $this->modelManager->getEntityManager('Application\Sonata\NewsBundle\Entity\Post');
        $repo = $em->getRepository('Application\Sonata\NewsBundle\Entity\Post');

        //get related lists
        $podcasts = $repo->getPostsByType('podcast');
        $news = $repo->getPostsByType('actualite');

        //parent::configureFormFields($formMapper); //can't use parent cause we cant change field order in formmaper after
        $formMapper
            ->with('Post', array(
                    'class' => 'col-md-8'
                ))
                ->add('author', 'sonata_type_model_list')
                ->add('title')
                ->add('short_title',"text", array('required' => false, 'label' => "Titre court"))
                ->add('abstract', "ckeditor", array())
                ->add('content', 'ckeditor', array())
            ->end()
            ->with('Status', array(
                    'class' => 'col-md-4'
                ))
                ->add('enabled', null, array('required' => false))
                ->add('image', 'sonata_type_model_list', array('required' => false), array(
                    'link_parameters' => array(
                        'context' => 'news'
                    )
                ))
                ->add('type',"choice", array('required' => false, 'label' => "Type","choices" => $this->choices))
                ->add('publicationDateStart', 'sonata_type_datetime_picker', array('dp_side_by_side' => true,'required' => false))
                ->add('commentsCloseAt', 'sonata_type_datetime_picker', array('dp_side_by_side' => true,'required' => false))
                ->add('commentsEnabled', null, array('required' => false))
                ->add('commentsDefaultStatus', 'sonata_news_comment_status', array('expanded' => true))
            ->end()

            ->with('Classification', array(
                'class' => 'col-md-4'
                ))
                ->add('tags', 'sonata_type_model_autocomplete', array(
                    'property' => 'name',
                    'multiple' => 'true',
                    'required' => false
                ))
                ->add('slug', null, array('required' => false, 'label' => 'Titre URL'))
                ->add('collection', 'sonata_type_model_list', array('required' => true, 'btn_add' => false))
            ->end()
            ->with('Articles associés', array(
                'class' => 'col-md-4'
                ))
                ->add('relatedPosts', 'sonata_type_model_autocomplete', array(
                    'label' => 'Actualités et Podcasts associés',
                    'property' => 'title',
                    'multiple' => true,
                    'required' => false
                ))
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('custom', 'string', array('template' => 'SonataNewsBundle:Admin:list_post_custom.html.twig', 'label' => 'Post'))
            ->add('type', null, array('label' => 'Type', 'values' => $this->choices))
            ->add('enabled', null, array('editable' => true))
            ->add('publicationDateStart')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);

        $datagridMapper
            ->add('type')
        ;
    }

}
