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

  public function getPostByCategory($category = "actualite", $limit = 500){
    $news = $this->createQueryBuilder('p')
            ->where('p.type = :category')
            ->setParameter('category', $category)
            ->addOrderBy('p.publicationDateStart', 'DESC')
            ->setMaxResults($limit)
        ;
    if($category == "actualite"){ //for old records with no type
        $news
            ->orWhere('p.type = :category')
            ->setParameter('category', "")
            ;
    }
    return $news;

  }

  public function listAll($order = 'DESC',$limit = 10){
    $q = $this->createQueryBuilder('p')
            ->addOrderBy('p.publicationDateStart', $order)
            ->setMaxResults($limit)
    ;
    $news = $q->getQuery()->getResult();
    return $news;
  }

}