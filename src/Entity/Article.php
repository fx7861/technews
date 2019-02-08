<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez saisir un titre.")
     * @Assert\Length(
     *     max="255",
     *     maxMessage="Votre titre ne doit pas dépasser {{ limit}} caractères."
     * )
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\File(
     *     maxSize = "1M",
     *     maxSizeMessage = "Votre image ne doit pas dépasser 1M.",
     *     mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg"
     *     },
     *     mimeTypesMessage = "Votre image doit être au format png ou jpeg."
     * )
     */
    private $featuredImage;

    /**
     * @ORM\Column(type="boolean")
     */
    private $spotlight;

    /**
     * @ORM\Column(type="boolean")
     */
    private $special;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Vous devez saisir un contenu pour votre article.")
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Membre", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tag", inversedBy="articles")
     */
    private $tag;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param mixed $tag
     */
    public function setTag($tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param mixed $categorie
     */
    public function setCategorie($categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMembre()
    {
        return $this->membre;
    }

    /**
     * @param mixed $membre
     */
    public function setMembre($membre): self
    {
        $this->membre = $membre;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getFeaturedImage()
    {
        return $this->featuredImage;
    }

    public function setFeaturedImage($featuredImage): self
    {
        $this->featuredImage = $featuredImage;

        return $this;
    }

    public function getSpotlight(): ?bool
    {
        return $this->spotlight;
    }

    public function setSpotlight(bool $spotlight): self
    {
        $this->spotlight = $spotlight;

        return $this;
    }

    public function getSpecial(): ?bool
    {
        return $this->special;
    }

    public function setSpecial(bool $special): self
    {
        $this->special = $special;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }
}
