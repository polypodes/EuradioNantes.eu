<?php
/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\NewsBundle\Entity;

use Sonata\NewsBundle\Entity\BasePostRepository;

class PostRepository extends BasePostRepository
{

  public function getPostsByType($type = null){
    $query = $this->createQueryBuilder('p')
        ->where('p.enabled = 1')
        ->addOrderBy('p.publicationDateStart', 'DESC')
        //->setMaxResults($limit)
    ;
    if ($type) {
        $query
            ->orWhere('p.type = :type')
            ->setParameter('type', $type)
        ;
    }
    return $query;
  }

  public function listAll($order = 'DESC', $limit = 10){
    $q = $this->createQueryBuilder('p')
        ->where('p.enabled = 1')
        ->addOrderBy('p.publicationDateStart', $order)
        ->setMaxResults($limit)
    ;
    $posts = $q->getQuery()->getResult();
    return $posts;
  }

  public function getPostsByCollection($collection = null, $order = 'DESC')
  {
    $q = $this->createQueryBuilder('p')
        ->where('p.enabled = 1')
        ->andWhere('p.collection = :collection')
        ->setParameter('collection', $collection)
        ->addOrderBy('p.publicationDateStart', $order)
    ;
    return $q;
  }

}
