<?php

namespace App\Entity;

use App\Repository\LeadsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LeadsRepository::class)
 */
class Leads
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id;

    

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $RetaicrmID;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
    /*public function getLeadId(): ?int
    {
        return $this->LeadId;
    }

    public function setLeadId(int $LeadID): self
    {
        $this->LeadId = $LeadID;

        return $this;
    }*/

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getRetaicrmID(): ?int
    {
        return $this->RetaicrmID;
    }

    public function setRetaicrmID(int $RetaicrmID): self
    {
        $this->RetaicrmID = $RetaicrmID;

        return $this;
    }
}
