<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypesRepository")
 */
class Types
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

  //  /**
  //   * @ORM\OneToMany(targetEntity="App\Entity\Products", mappedBy="type")
  //   */
  //  private $products;

   // public function __construct()
   // {
   //     $this->products = new ArrayCollection();
   // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

  //  /**
  //   * @return Collection|Products[]
  //   */
  //  public function getProducts(): Collection
  //  {
 //       return $this->products;
  //  }
//
  //  public function addProduct(Products $product): self
 //   {
  //      if (!$this->products->contains($product)) {
  //          $this->products[] = $product;
  //          $product->setType($this);
  //      }

   //     return $this;
  //  }

  //  public function removeProduct(Products $product): self
 //   {
  //      if ($this->products->contains($product)) {
  //          $this->products->removeElement($product);
  //          // set the owning side to null (unless already changed)
  //          if ($product->getType() === $this) {
  //              $product->setType(null);
  //         }
   //     }
//
   //     return $this;
   // }
}
