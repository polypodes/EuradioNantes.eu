<?php

namespace RadioSolution\PodcastBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PodcastMarkerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->setAttribute('display_table', $options['display_table'])
            ->add('start', 'text', array('label' => 'Début'))
            ->add('end', 'text', array('label' => 'Fin'))
            ->add('title', 'text', array('label' => 'Titre'))
            ->add('text', 'textarea', array('label' => 'Description'))
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        //$view->vars['display_table'] = $options['display_table'];
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        //$resolver->setDefaults([
        //    'display_table' => false,
        //]);
    }

    public function getParent()
    {
        return 'form';
    }

    public function getName()
    {
        return 'podcast_marker';
    }
}
