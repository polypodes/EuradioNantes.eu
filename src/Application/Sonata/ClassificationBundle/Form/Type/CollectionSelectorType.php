<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\ClassificationBundle\Form\Type;

use Sonata\ClassificationBundle\Model\CollectionInterface;
use Sonata\CoreBundle\Model\ManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Select a collection.
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class CollectionSelectorType extends AbstractType
{
    protected $manager;

    /**
     * @param ManagerInterface $manager
     */
    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $that = $this;

        $resolver->setDefaults(array(
            'context'           => null,
            'collection'          => null,
            'choice_list'       => function (Options $opts, $previousValue) use ($that) {
                return new SimpleChoiceList($that->getChoices($opts));
            },
        ));
    }

    /**
     * @param Options $options
     *
     * @return array
     */
    public function getChoices(Options $options)
    {
        if (!$options['collection'] instanceof CollectionInterface) {
            return array();
        }

        if ($options['context'] === null) {
            $collections = $this->manager->getRootCollections();
        } else {
            $collections = array($this->manager->getRootCollection($options['context']));
        }

        $choices = array();

        foreach ($collections as $collection) {
            $choices[$collection->getId()] = sprintf('%s (%s)', $collection->getName(), $collection->getContext()->getId());

            $this->childWalker($collection, $options, $choices);
        }

        return $choices;
    }

    /**
     * @param CollectionInterface $collection
     * @param Options           $options
     * @param array             $choices
     * @param int               $level
     */
    private function childWalker(CollectionInterface $collection, Options $options, array &$choices, $level = 2)
    {
        if ($collection->getChildren() === null) {
            return;
        }

        foreach ($collection->getChildren() as $child) {
            if ($options['collection'] && $options['collection']->getId() == $child->getId()) {
                continue;
            }

            $choices[$child->getId()] = sprintf('%s %s', str_repeat('-', 1 * $level), $child);

            $this->childWalker($child, $options, $choices, $level + 1);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'sonata_type_model';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sonata_collection_selector';
    }
}
