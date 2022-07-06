<?php

namespace App\Entity;

use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CurrencyRepository;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $currency_code;

    #[ORM\Column(type: 'float')]
    private $exchange_rate;

    public function __construct($name, $currency_code, $exchange_rate)
    {
        $this->id = Uuid::uuid4();
        $this->name = $name;
        $this->currency_code = $currency_code;
        $this->exchange_rate = $exchange_rate;
    }

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

    public function getCurrencyCode(): ?string
    {
        return $this->currency_code;
    }

    public function setCurrencyCode(string $currency_code): self
    {
        $this->currency_code = $currency_code;

        return $this;
    }

    public function getExchangeRate(): ?float
    {
        return $this->exchange_rate;
    }

    public function setExchangeRate(float $exchange_rate): self
    {
        $this->exchange_rate = $exchange_rate;

        return $this;
    }
}
