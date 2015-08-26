<?php

namespace RadioSolution\PodcastBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PodcastPlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder
        //    ->setAttribute('display_table', $options['display_table'])
        //;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        //$view->vars['podcast'] = $options['podcast'];
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        //$resolver->setDefaults([
        //    'podcast' => null,
        //]);
    }

    public function getName()
    {
        return 'podcast_player';
    }
}
