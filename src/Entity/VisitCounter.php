<?php

namespace App\Entity;

use App\Repository\VisitCounterRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VisitCounterRepository::class)
 */
class VisitCounter
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sum = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ip_adress;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSum(): ?int
    {
        return $this->sum;
    }

    public function setSum(?int $sum): self
    {
        $this->sum = $sum;

        return $this;
    }

    public function getIpAdress(): ?string
    {
        return $this->ip_adress;
    }

    public function setIpAdress(?string $ip_adress): self
    {
        $this->ip_adress = $ip_adress;

        return $this;
    }
}
