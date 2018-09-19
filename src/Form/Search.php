<?php
/**
 * Created by PhpStorm.
 * User: Krzysztof
 * Date: 19.09.2018
 * Time: 12:09
 */

namespace App\Form;


use Doctrine\ORM\QueryBuilder;

class Search {

    protected $search;

    /**
     * @return mixed
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param mixed $search
     */
    public function setSearch($search): void
    {
        $this->search = $search;
    }

    public function toQuery(QueryBuilder $qb) {
        $qb->orWhere($qb->expr()->like('product.name', $qb->expr()->literal('%'.$this->search.'%')));
        $qb->orWhere($qb->expr()->like('type.name', $qb->expr()->literal('%'.$this->search.'%')));
        return $qb->getQuery()->getResult();
    }

}