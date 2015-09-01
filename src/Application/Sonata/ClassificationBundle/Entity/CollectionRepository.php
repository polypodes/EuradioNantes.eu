<?php

namespace Application\Sonata\ClassificationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CollectionRepository.
 */
class CollectionRepository extends EntityRepository
{

    public function findAllByContext($context)
    {
        return $this
            ->queryAllEnabledByContext($context)
            ->getQuery()
            ->getResult()
        ;
    }

    public function queryAllEnabledByContext($context)
    {
        return $this->createQueryBuilder('e')
            ->where('e.enabled = true')
            ->andWhere('e.context = :context')
            ->setParameter('context', $context);
        ;
    }
}
