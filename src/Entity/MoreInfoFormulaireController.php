<?php

namespace App\Entity;

use App\Repository\MoreInfoFormulaireControllerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoreInfoFormulaireControllerRepository::class)]
class MoreInfoFormulaireController
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 125, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 125, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $serviceTypeSuperSite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $serviceTypeAutre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getServiceTypeSuperSite(): ?string
    {
        return $this->serviceTypeSuperSite;
    }

    public function setServiceTypeSuperSite(?string $serviceTypeSuperSite): static
    {
        $this->serviceTypeSuperSite = $serviceTypeSuperSite;

        return $this;
    }

    public function getServiceTypeAutre(): ?string
    {
        return $this->serviceTypeAutre;
    }

    public function setServiceTypeAutre(?string $serviceTypeAutre): static
    {
        $this->serviceTypeAutre = $serviceTypeAutre;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
