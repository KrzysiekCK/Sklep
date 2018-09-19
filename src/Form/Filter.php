<?php
/**
 * Created by PhpStorm.
 * User: Krzysztof
 * Date: 17.09.2018
 * Time: 12:35
 */

namespace App\Form;


use Doctrine\ORM\QueryBuilder;

class Filter {
    protected $offers;
    protected $types;
    protected $colors;
    protected $sizes;
    protected $price;

    /**
     * @return mixed
     */
    public function getOffers()
    {
        return $this->offers;
    }

    /**
     * @param mixed $offers
     */
    public function setOffers($offers): void
    {
        $this->offers = $offers;
    }

    /**
     * @return mixed
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @param mixed $types
     */
    public function setTypes($types): void
    {
        $this->types = $types;
    }

    /**
     * @return mixed
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * @param mixed $colors
     */
    public function setColors($colors): void
    {
        $this->colors = $colors;
    }

    /**
     * @return mixed
     */
    public function getSizes()
    {
        return $this->sizes;
    }

    /**
     * @param mixed $sizes
     */
    public function setSizes($sizes): void
    {
        $this->sizes = $sizes;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    public function toQuery(QueryBuilder $qb) {
        //oferta
        if ($this->offers[0] == true) $qb->andWhere('magazine.new = :new')->setParameter('new', true);
        if ($this->offers[1] == true) $qb->andWhere('magazine.sale > :sale')->setParameter('sale', 0);

        //produkty
        $orX = $qb->expr()->orX();
        foreach ($this->types as $key => $value) {
            if ($value == true) $orX->add($qb->expr()->eq('product.type', $key));
        }
        $qb->andWhere($orX);

        //kolor
        $orX = $qb->expr()->orX();
        foreach ($this->colors as $key => $value) {
            if ($value == true) $orX->add($qb->expr()->eq('magazine.color', $key));
        }
        $qb->andWhere($orX);

        //rozmiar
        $orX = $qb->expr()->orX();
        if ($this->sizes[0] == true) $orX->add($qb->expr()->gt('magazine.unisize', 0));
        if ($this->sizes[1] == true) $orX->add($qb->expr()->gt('magazine.xs', 0));
        if ($this->sizes[2] == true) $orX->add($qb->expr()->gt('magazine.s', 0));
        if ($this->sizes[3] == true) $orX->add($qb->expr()->gt('magazine.m', 0));
        if ($this->sizes[4] == true) $orX->add($qb->expr()->gt('magazine.l', 0));
        if ($this->sizes[5] == true) $orX->add($qb->expr()->gt('magazine.xl', 0));
        $qb->andWhere($orX);

        return $qb->getQuery()->getResult();
    }

}