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

use Sonata\AdminBundle\Form\FormMapper;

use Sonata\NewsBundle\Admin\PostAdmin as BaseAdmin;

class PostAdmin extends BaseAdmin
{

    protected $datagridValues = array(
        '_page' => 1,            // display the first page (default = 1)
        '_sort_order' => 'DESC', // reverse order (default = 'ASC')
        '_sort_by' => 'publicationDateStart'  // name of the ordered field
                                 // (default = the model's id field, if any)
        // the '_sort_by' key can be of the form 'mySubModel.mySubSubModel.myField'.
    );
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->with('Post', array(
                    'class' => 'col-md-8'
                ))
                ->add('admin_title',"text", array('required' => false, 'label' => "Titre admin"))
                ->add('abstract',"ckeditor", array())
                ->add('content',"ckeditor", array())
            ->end()
            ->with('Status', array(
                    'class' => 'col-md-4'
                ))
                ->add('commentsCloseAt', 'sonata_type_datetime_picker', array('required' => false, 'dp_side_by_side' => true))
            ->end()
            ->with('Classification', array(
                'class' => 'col-md-4'
                ))
                ->add('tags', 'sonata_type_model_autocomplete', array(
                    'property' => 'name',
                    'multiple' => 'true',
                    'required' => false
                ))
            ->end()
        ;
    }

   
}
