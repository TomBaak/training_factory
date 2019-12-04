<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
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
    private $street;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $postalcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $number;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lesson", mappedBy="location_id", orphanRemoval=true)
     */
    private $location_id;

    public function __construct()
    {
        $this->location_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalcode(): ?string
    {
        return $this->postalcode;
    }

    public function setPostalcode(string $postalcode): self
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection|Lesson[]
     */
    public function getLocationId(): Collection
    {
        return $this->location_id;
    }

    public function addLocationId(Lesson $locationId): self
    {
        if (!$this->location_id->contains($locationId)) {
            $this->location_id[] = $locationId;
            $locationId->setLocationId($this);
        }

        return $this;
    }

    public function removeLocationId(Lesson $locationId): self
    {
        if ($this->location_id->contains($locationId)) {
            $this->location_id->removeElement($locationId);
            // set the owning side to null (unless already changed)
            if ($locationId->getLocationId() === $this) {
                $locationId->setLocationId(null);
            }
        }

        return $this;
    }
}
