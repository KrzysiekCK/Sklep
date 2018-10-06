<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MagazineRepository")
 */
class Magazine
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="magazines")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Color", inversedBy="magazines")
     */
    private $color;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     */
    private $sale;

    /**
     * @ORM\Column(type="boolean")
     */
    private $new;

    /**
     * @ORM\Column(type="integer")
     */
    private $unisize;

    /**
     * @ORM\Column(type="integer")
     */
    private $xs;

    /**
     * @ORM\Column(type="integer")
     */
    private $s;

    /**
     * @ORM\Column(type="integer")
     */
    private $m;

    /**
     * @ORM\Column(type="integer")
     */
    private $l;

    /**
     * @ORM\Column(type="integer")
     */
    private $xl;

    private $productRB;

    private $colorRB;

    /**
     * @return mixed
     */
    public function getProductRB()
    {
        return $this->productRB;
    }

    /**
     * @param mixed $productRB
     */
    public function setProductRB($productRB): void
    {
        $this->productRB = $productRB;
    }

    /**
     * @return mixed
     */
    public function getColorRB()
    {
        return $this->colorRB;
    }

    /**
     * @param mixed $colorRB
     */
    public function setColorRB($colorRB): void
    {
        $this->colorRB = $colorRB;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSale(): ?int
    {
        return $this->sale;
    }

    public function setSale(int $sale): self
    {
        $this->sale = $sale;

        return $this;
    }

    public function getNew(): ?bool
    {
        return $this->new;
    }

    public function setNew(bool $new): self
    {
        $this->new = $new;

        return $this;
    }

    public function getUnisize(): ?int
    {
        return $this->unisize;
    }

    public function setUnisize(int $unisize): self
    {
        $this->unisize = $unisize;

        return $this;
    }

    public function getXs(): ?int
    {
        return $this->xs;
    }

    public function setXs(int $xs): self
    {
        $this->xs = $xs;

        return $this;
    }

    public function getS(): ?int
    {
        return $this->s;
    }

    public function setS(int $s): self
    {
        $this->s = $s;

        return $this;
    }

    public function getM(): ?int
    {
        return $this->m;
    }

    public function setM(int $m): self
    {
        $this->m = $m;

        return $this;
    }

    public function getL(): ?int
    {
        return $this->l;
    }

    public function setL(int $l): self
    {
        $this->l = $l;

        return $this;
    }

    public function getXl(): ?int
    {
        return $this->xl;
    }

    public function setXl(int $xl): self
    {
        $this->xl = $xl;

        return $this;
    }
}
