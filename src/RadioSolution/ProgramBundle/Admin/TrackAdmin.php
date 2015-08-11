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
use RadioSolution\ProgramBundle\Entity\Track;
use Symfony\Component\Validator\Constraints\DateTime;

class TrackAdmin extends Admin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Description')
            ->add('title', null, array('label' => 'Titre', 'required' => true))
            ->add('album', 'sonata_type_model_autocomplete', array('property'=>['title']))
            ->add('artist', null, array('label' => 'Artiste', 'required' => false))
            ->add('genre', null, array('label' => 'Genre', 'required' => false))
            ->add('label', null, array('label' => 'Label', 'required' => false))
            ->add('manufacturer', null, array('label' => 'Production', 'required' => false))
            ->add('publisher', null, array('label' => 'Édition', 'required' => false))
            ->add('releaseDate', 'datetime', array('label' => 'Date de sortie', 'required' => false))
            ->add('runningTime', 'number', array('label' => 'Durée (secondes)', 'required' => false))
            ->add('studio', null, array('label' => 'Studio', 'required' => false))
            ->add('trackSequence', 'number', array('label' => "Position dans l'album", 'required' => false))
            ->end()
            /*
            ->add('WeeklyBroadcast', 'sonata_type_collection', array('label' => 'Diffusion hebdomadaire', 'required' => false, 'by_reference' =>true), array(
                'edit' => 'inline',
                'inline' => 'table',
            ))
            */
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('album')
            ->add('artist')
            ->add('genre')
            ->add('label')
            ->add('manufacturer')
            ->add('publisher')
            ->add('studio')
        ;
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
            ->addIdentifier('title')
            ->add('album')
            ->add('artist')
            ->add('genre')
            ->add('label')
            ->add('manufacturer')
            ->add('publisher')
            ->add('studio')
            ->add('trackSequence')
            ;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('artist')
            ->assertLength(array('max' => 255))
            ->end()
            ->with('genre')
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
            ->with('trackSequence')
            ->assertGreaterThan(array('value' => 0))
            ->end()
            ->with('runningTime')
            ->assertGreaterThan(array('value' => 0))
            ->end()
            ->with('title')
            ->assertLength(array('max' => 255))
            ->assertNotNull()
            ->assertNotBlank()
            ->end()
        ;
    }
}
