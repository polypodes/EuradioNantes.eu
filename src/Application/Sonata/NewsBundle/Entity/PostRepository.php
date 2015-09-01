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

  public function getPostsByType($type = "actualite"){
    $query = $this->createQueryBuilder('p')
        ->where('p.type = :category')
        ->setParameter('category', $type)
        ->addOrderBy('p.publicationDateStart', 'DESC')
        //->setMaxResults($limit)
    ;
    if ($type == "actualite") { //for old records with no type
        $query
            ->orWhere('p.type = :type')
            ->setParameter('type', "")
        ;
    }
    return $query;
  }

  public function listAll($order = 'DESC', $limit = 10){
    $q = $this->createQueryBuilder('p')
        ->addOrderBy('p.publicationDateStart', $order)
        ->setMaxResults($limit)
    ;
    $posts = $q->getQuery()->getResult();
    return $posts;
  }

  public function getPostsByCollection($collection = null, $order = 'DESC')
  {
    $q = $this->createQueryBuilder('p')
        ->where('p.collection = :collection')
        ->setParameter('collection', $collection)
        ->addOrderBy('p.publicationDateStart', $order)
    ;
    return $q;
  }

}
