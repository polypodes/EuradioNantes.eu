<?php
/**
 * This file is part of the EuradioNantes.eu package.
 *
 * (c) 2015 Les Polypodes
 * Made in Nantes, France - http://lespolypodes.com
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 *
 * File created by ronan@lespolypodes.com (10/08/2015 - 15:11)
 */

namespace RadioSolution\ProgramBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use RadioSolution\ProgramBundle\Entity\Playlist;
use Symfony\Component\Validator\Constraints\DateTime;

class PlaylistAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        // define group zoning
        $formMapper
            ->with($this->trans('Playlist'))->end()
            ->with($this->trans('Albums'))->end()
        ;

        $formMapper
            ->with($this->trans('Playlist'))
                ->add('published', null, array('label' => 'Publié'))
                ->add('title', null, array('label' => 'Titre', 'required' => true))
                ->add('slug', null, array('label' => 'Titre URL', 'required' => false))
                ->add('featuredFrom', 'sonata_type_date_picker', array('label' => 'Playlist du '))
                ->add('featuredTo', 'sonata_type_date_picker', array('label' => 'au : (inclus)'))
            ->end()
            ->with($this->trans('Albums'))
                /*
                ->add(
                    'albums',
                    'sonata_type_model',
                    array(
                        'expanded' => true,
                        'multiple' => true,
                        //'sortable' => 'albumSequence',
                        'btn_add' => 'Créer',
                        'compound' => true
                    )
                )
                ->add('albums', 'sonata_type_collection',
                    array('label' => 'Albums', 'required' => false), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'btn_list' => true,
                    'sortable' => 'albumSequence'
                ))
                */
                ->add('albums', 'sonata_type_model_autocomplete', array('property'=>'title','multiple'=>true))
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('featuredFrom')
            ->add('featuredTo')
        ;
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
            ->addIdentifier('title')
            ->add('featuredPeriod', 'string', array('label' => 'Album de la semaine'))
            ->add('published', 'boolean', array('label' => 'Publié', 'editable' => true))
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
        $errorElement

            ->with('featuredFrom')
            ->assertDateTime()
            ->end()
            ->with('featuredTo')
            ->assertDateTime()
            ->end()
            ->with('title')
            ->assertLength(array('max' => 255))
            ->assertNotNull()
            ->assertNotBlank()
            ->end()
        ;
    }
}
