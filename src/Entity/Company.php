<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    public const TYPE_PROVIDER = 'provider';
    public const TYPE_CLIENT = 'client';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $owner = null;

    #[ORM\Column(length: 150)]
    private string $name = '';

    #[ORM\Column(length: 30)]
    private string $type = self::TYPE_PROVIDER;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $taxId = null;

    #[ORM\Column(length: 2)]
    private string $countryCode = 'BR';

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $address = null;

    #[Assert\Email(message: 'Informe um e-mail válido para a empresa.')]
    #[ORM\Column(length: 180, nullable: true)]
    private ?string $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = trim($name);

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTaxId(): ?string
    {
        return $this->taxId;
    }

    public function setTaxId(?string $taxId): self
    {
        $this->taxId = $taxId !== null ? trim($taxId) : null;

        return $this;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = mb_strtoupper(trim($countryCode));

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address !== null ? trim($address) : null;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email !== null && $email !== '' ? mb_strtolower(trim($email)) : null;

        return $this;
    }
}
