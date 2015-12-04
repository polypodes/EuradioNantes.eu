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
    protected $datagridValues = array(
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'createdAt' // field name
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        // define group zoning
        $subject = $this->getSubject();
        $isNew = empty($subject->getTitle());

        if ($isNew) {
            $formMapper
            ->with($this->trans('Recherche'))
                ->add('search', 'text', array('label' => 'Recherche', 'required' => false))
                ->setHelps(array(
                    'search' => $this->trans('Recherche au format « Artiste - Album » ou « Artiste - Chanson - Album »'),
                ))
            ->end();
        }

        $formMapper
            //->with($this->trans('Recherche'))->end()
            ->with($this->trans('Album'))->end()
            ->with($this->trans('Description'))->end()
            ->with($this->trans('Tracks'))->end()
            //->with($this->trans('Playlists'))->end()
        ;

        $formMapper
            ->with($this->trans('Album'))
                ->add('published', null, array('label' => 'Publié'))
                ->add('title', null, array('label' => 'Titre', 'required' => !$isNew))
                ->add('slug', null, array('label' => 'Titre URL', 'required' => false))
                ->add('artist', null, array('label' => 'Artiste', 'required' => false))
                ->add('label', null, array('label' => 'Label', 'required' => false))
                ->add('labelId', null, array('label' => 'Association à un label du mois', 'required' => false))
                ->add('manufacturer', null, array('label' => 'Production', 'required' => false))
                ->add('publisher', null, array('label' => 'Édition', 'required' => false))
                ->add('releaseDate', 'sonata_type_date_picker', array('label' => 'Date de sortie', 'required' => false))
                ->add('featuredFrom', 'sonata_type_date_picker', array('label' => 'Album de la Semaine: du ', 'required' => false))
                ->add('featuredTo', 'sonata_type_date_picker', array('label' => 'au : (inclus)', 'required' => false))
                ->add('studio', null, array('label' => 'Studio', 'required' => false))
                ->add('thumbnailUrl', 'url', array('label' => 'Vignette', 'required' => false))
                ->add('image', 'sonata_type_model_list', array('label' => 'Image principale'), array('link_parameters' => array('provider' => 'sonata.media.provider.image')))
            ->end()
            ->with($this->trans('Description'))
                ->add('resume', 'ckeditor', array('label' => 'Résumé', 'required' => false, 'config_name' => 'mini'))
                ->add('content', 'ckeditor', array('label' => 'Description', 'required' => false, 'config_name' => 'plus'))
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
                ->add(
                    'tracks',
                    'sonata_type_model',
                    array(
                        'expanded' => true,
                        'multiple' => true,
                        'sortable' => 'trackSequence',
                        'btn_add' => 'Créer',
                        'compound' => true
                    )
                )
                */
                ->add('tracks', 'sonata_type_collection', array('label' => 'Pistes', 'required'=>false, 'btn_add' => 'Ajouter une piste'), array('edit' => 'inline', 'inline' => 'table', 'sortable' => 'trackSequence'))
            ->end()
            //->with($this->trans('Playlists'))
            //    ->add('playlists', 'sonata_type_model_autocomplete', array('property'=>'title','multiple'=>true,'required'=>false))
            //->end()
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
            ->add('featuredFrom')
            ->add('featuredTo')
            ->add('published')
        ;
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
            ->addIdentifier('title')
            ->add('artist')
            ->add('label')
            ->add('manufacturer')
            ->add('publisher')
            ->add('studio')
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

    public function prePersist($album)
    {
        //if (!empty($album->getSearch())) {
        //    $retriever = $this->container->get('radiosolution.program.trackRetriever');
        //    list($currentTrackTitle, $terms, $album, $images, $tracks) = $retriever->search($terms);
        //    die(var_dump($currentTrackTitle, $terms, $album, $images, $tracks));
        //
        //    $this->setTitle($album);
        //}
    }
}
