<?php

namespace App\Entity;

use App\Repository\EnseigneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: EnseigneRepository::class)]
#[ORM\Table(name: 'enseigne')]
class Enseigne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private string $name;

    #[ORM\Column(length: 36, unique: true)]
    private string $uuid;

    #[ORM\Column(length: 255)]
    private string $trackingUrl = '';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $qrFilename = null;

    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $createdAt = null;

    /** @var Collection<int, QrScan> */
    #[ORM\OneToMany(mappedBy: 'enseigne', targetEntity: QrScan::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $scans;

    /** @var Collection<int, QrShare> */
    #[ORM\OneToMany(mappedBy: 'enseigne', targetEntity: QrShare::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $shares;

    public function __construct()
    {
        $this->uuid = Uuid::v4()->toRfc4122();
    $this->scans = new ArrayCollection();
    $this->shares = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getUuid(): string { return $this->uuid; }
    public function getTrackingUrl(): string { return $this->trackingUrl; }
    public function setTrackingUrl(string $trackingUrl): self { $this->trackingUrl = $trackingUrl; return $this; }
    public function getQrFilename(): ?string { return $this->qrFilename; }
    public function setQrFilename(?string $qrFilename): self { $this->qrFilename = $qrFilename; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }

    /** @return Collection<int, QrScan> */
    public function getScans(): Collection { return $this->scans; }
    public function addScan(QrScan $scan): self { if(!$this->scans->contains($scan)){ $this->scans->add($scan); $scan->setEnseigne($this);} return $this; }
    public function removeScan(QrScan $scan): self { if($this->scans->removeElement($scan)){ if($scan->getEnseigne() === $this){ $scan->setEnseigne(null);} } return $this; }

    public function getTotalScans(): int { return $this->scans->count(); }
    /** @return Collection<int, QrShare> */
    public function getShares(): Collection { return $this->shares; }
    public function addShare(QrShare $share): self { if(!$this->shares->contains($share)){ $this->shares->add($share); $share->setEnseigne($this);} return $this; }
    public function removeShare(QrShare $share): self { if($this->shares->removeElement($share)){ if($share->getEnseigne() === $this){ $share->setEnseigne(null);} } return $this; }
    public function getTotalShares(): int { return $this->shares->count(); }

    public function __toString(): string { return $this->name; }
}
