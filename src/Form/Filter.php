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
    protected $priceFrom;
    protected $priceTo;
    protected $orderCategory;
    protected $orderDirection;

    /**
     * Filter constructor.
     * @param $offers
     * @param $types
     * @param $colors
     * @param $sizes
     * @param $priceFrom
     * @param $priceTo
     * @param $orderCategory
     * @param $orderDirection
     */
    public function __construct($offers, $types, $colors, $sizes, $priceFrom, $priceTo, $orderCategory, $orderDirection) {
        $this->offers = $offers;
        $this->types = $types;
        $this->colors = $colors;
        $this->sizes = $sizes;
        $this->priceFrom = $priceFrom;
        $this->priceTo = $priceTo;
        $this->orderCategory = $orderCategory;
        $this->orderDirection = $orderDirection;
    }

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
    public function getPriceFrom()
    {
        return $this->priceFrom;
    }

    /**
     * @param mixed $priceFrom
     */
    public function setPriceFrom($priceFrom): void
    {
        $this->priceFrom = $priceFrom;
    }

    /**
     * @return mixed
     */
    public function getPriceTo()
    {
        return $this->priceTo;
    }

    /**
     * @param mixed $priceTo
     */
    public function setPriceTo($priceTo): void
    {
        $this->priceTo = $priceTo;
    }

    /**
     * @return mixed
     */
    public function getOrderCategory()
    {
        return $this->orderCategory;
    }

    /**
     * @param mixed $orderCategory
     */
    public function setOrderCategory($orderCategory): void
    {
        $this->orderCategory = $orderCategory;
    }

    /**
     * @return mixed
     */
    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    /**
     * @param mixed $orderDirection
     */
    public function setOrderDirection($orderDirection): void
    {
        $this->orderDirection = $orderDirection;
    }

    public function toQuery(QueryBuilder $qb) {
        //oferta
        $orX = $qb->expr()->orX();
        if ($this->offers[0] == true) $orX->add($qb->expr()->eq('magazine.new', true));
        if ($this->offers[1] == true) $orX->add($qb->expr()->gt('magazine.sale', 0));
        if ($orX->count() > 0) $qb->andWhere($orX);

        //produkty
        $orX = $qb->expr()->orX();
        foreach ($this->types as $key => $value) {
            if ($value == true) $orX->add($qb->expr()->eq('product.type', $key));
        }
        if ($orX->count() > 0) $qb->andWhere($orX);

        //kolor
        $orX = $qb->expr()->orX();
        foreach ($this->colors as $key => $value) {
            if ($value == true) $orX->add($qb->expr()->eq('magazine.color', $key));
        }
        if ($orX->count() > 0) $qb->andWhere($orX);

        //rozmiar
        $orX = $qb->expr()->orX();
        if ($this->sizes[0] == true) $orX->add($qb->expr()->gt('magazine.unisize', 0));
        if ($this->sizes[1] == true) $orX->add($qb->expr()->gt('magazine.xs', 0));
        if ($this->sizes[2] == true) $orX->add($qb->expr()->gt('magazine.s', 0));
        if ($this->sizes[3] == true) $orX->add($qb->expr()->gt('magazine.m', 0));
        if ($this->sizes[4] == true) $orX->add($qb->expr()->gt('magazine.l', 0));
        if ($this->sizes[5] == true) $orX->add($qb->expr()->gt('magazine.xl', 0));
        if ($orX->count() > 0) $qb->andWhere($orX);

        //cena
        $qb->andWhere($qb->expr()->gte('product.price', $this->priceFrom));
        $qb->andWhere($qb->expr()->lte('product.price', $this->priceTo));

        //sortowanie
        $orderBy = ($this->orderCategory == 'product.price') ? '(100 - magazine.sale) * product.price / 100' : $this->orderCategory;
        $qb->orderBy($orderBy, $this->orderDirection);

        return $qb->getQuery()->getResult();
    }

}