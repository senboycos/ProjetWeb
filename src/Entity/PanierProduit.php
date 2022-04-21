<?php

namespace App\Entity;

use App\Repository\PanierPorduitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="asso_panier_produit",
 *     uniqueConstraints={
 *     @ORM\UniqueConstraint(name="ahe_idx", columns={"id_panier", "id_produit"})
 *     }
 * )
 * @ORM\Entity(repositoryClass=PanierPorduitRepository::class)
 */
class PanierProduit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Panier::class)
     * @ORM\JoinColumn(name="id_panier" ,nullable=false)
     */
    private $panier;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class)
     * @ORM\JoinColumn(name="id_produit" ,nullable=false)
     */
    private $produit;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): self
    {
        $this->panier = $panier;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }
}
