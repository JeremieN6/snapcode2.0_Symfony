<?php

namespace App\Entity;

use App\Repository\QrShareRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QrShareRepository::class)]
#[ORM\Table(name: 'qr_share')]
class QrShare
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'shares')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Enseigne $enseigne = null;

    #[ORM\Column(length: 30)]
    private string $channel; // whatsapp, sms, email, copy, other

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getEnseigne(): ?Enseigne { return $this->enseigne; }
    public function setEnseigne(?Enseigne $enseigne): self { $this->enseigne = $enseigne; return $this; }
    public function getChannel(): string { return $this->channel; }
    public function setChannel(string $channel): self { $this->channel = $channel; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
}
