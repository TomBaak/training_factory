<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="binary")
     */
    private $imageString;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageString()
    {
        return $this->imageString;
    }

    public function setImageString($imageString): self
    {
        $this->imageString = $imageString;

        return $this;
    }
}
