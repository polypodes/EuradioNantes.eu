<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\NewsBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;

class CommentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'form.comment.name'))
            ->add('email', 'email', array('required' => false, 'label' => 'form.comment.email'))
            //->add('url', 'url', array('required' => false, 'label' => 'form.comment.url'))
            ->add('message', null, array('label' => 'form.comment.message'))
            ->add('recaptcha', 'ewz_recaptcha', array(
                'constraints' => array(
                    new RecaptchaTrue()
                )
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sonata_post_comment';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {;
        $resolver->setDefaults(array(
            'translation_domain' => 'SonataNewsBundle'
        ));
    }
}
