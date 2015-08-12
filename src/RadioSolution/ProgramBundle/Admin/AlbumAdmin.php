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
use RadioSolution\ProgramBundle\Entity\Album;
use Symfony\Component\Validator\Constraints\DateTime;

class AlbumAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        // define group zoning
        $formMapper
            ->with($this->trans('Album'))->end()
            ->with($this->trans('Tracks'))->end()
        ;

        $formMapper
            ->with($this->trans('Album'))
            ->add('title', null, array('label' => 'Titre', 'required' => true))
            ->add('artist', null, array('label' => 'Artiste', 'required' => false))
            ->add('label', null, array('label' => 'Label', 'required' => false))
            ->add('manufacturer', null, array('label' => 'Production', 'required' => false))
            ->add('publisher', null, array('label' => 'Édition', 'required' => false))
            ->add('releaseDate', 'sonata_type_date_picker', array('label' => 'Date de sortie', 'required' => false))
            ->add('featuredFrom', 'sonata_type_date_picker', array('label' => 'Album de la Semaine: du ', 'required' => false))
            ->add('featuredTo', 'sonata_type_date_picker', array('label' => 'au : (inclus)', 'required' => false))
            ->add('studio', null, array('label' => 'Studio', 'required' => false))
            ->end()
            ->with($this->trans('Tracks'))
            /*
            ->add(
                'tracks',
                'sonata_type_model',
                array(
                    'expanded' => true,
                    'multiple' => true,
                    //'sortable' => 'trackSequence',
                    'btn_add' => 'Créer',
                    'compound' => true
                )
            )
            ->add('tracks', 'sonata_type_collection',
                array('label' => 'Tracks', 'required' => false), array(
                'edit' => 'inline',
                'inline' => 'table',
                'btn_list' => true,
                'sortable' => 'trackSequence'
            ))
            */

            ->add('tracks', 'sonata_type_collection',
                array('label' => 'Tracks', 'required' => false), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'btn_list' => true,
                    'sortable' => 'trackSequence'
                ))


            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('artist')
            ->add('label')
            ->add('manufacturer')
            ->add('publisher')
            ->add('studio')
        ;
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
            ->addIdentifier('title')
            ->add('artist')
            ->add('label')
            ->add('manufacturer')
            ->add('publisher')
            ->add('studio');
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('artist')
            ->assertLength(array('max' => 255))
            ->end()
            ->with('label')
            ->assertLength(array('max' => 255))
            ->end()
            ->with('manufacturer')
            ->assertLength(array('max' => 255))
            ->end()
            ->with('publisher')
            ->assertLength(array('max' => 255))
            ->end()
            ->with('releaseDate')
            ->assertDateTime()
            ->end()
            ->with('studio')
            ->assertLength(array('max' => 255))
            ->end()
            ->with('title')
            ->assertLength(array('max' => 255))
            ->assertNotNull()
            ->assertNotBlank()
            ->end()
        ;
    }
}
