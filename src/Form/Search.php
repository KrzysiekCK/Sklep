<?php
/**
 * Created by PhpStorm.
 * User: Krzysztof
 * Date: 19.09.2018
 * Time: 12:09
 */

namespace App\Form;


use Doctrine\ORM\QueryBuilder;

class Search
{

    protected $search;

    /**
     * Search constructor.
     * @param $search
     */
    public function Ł__construct($search)
    {
        $this->search = $search;
    }


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

    public function toQuery(QueryBuilder $qb)
    {
        $qb->orWhere($qb->expr()->like('product.name', $qb->expr()->literal('%' . $this->search . '%')));
        $qb->orWhere($qb->expr()->like('type.name', $qb->expr()->literal('%' . $this->search . '%')));
        $qb->orderBy('product.price', 'ASC');
        return $qb->getQuery()->getResult();
    }

}