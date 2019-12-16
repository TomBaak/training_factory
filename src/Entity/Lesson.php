<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LessonRepository")
 */
class Lesson
{
	
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $time;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_persons;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="lesson")
     * @ORM\JoinColumn(nullable=false)
     */
    private $instructor;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Registration", mappedBy="lesson")
     */
    private $registrations;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Training", inversedBy="training")
     * @ORM\JoinColumn(nullable=false)
     */
    private $training;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="location")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    public function __construct()
    {
        $this->registrations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location->getStreet();
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getMaxPersons(): ?int
    {
        return $this->max_persons;
    }

    public function setMaxPersons(int $max_persons): self
    {
        $this->max_persons = $max_persons;

        return $this;
    }

    public function getInstructor(): ?Person
    {
        return $this->instructor;
    }

    public function setInstructor(?Person $instructor): self
    {
        $this->instructor = $instructor;

        return $this;
    }

    /**
     * @return Collection|Registration[]
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function addLesson(Registration $registration): self
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations[] = $registration;
			$registration->setLesson($this);
        }

        return $this;
    }

    public function removeLesson(Registration $lesson): self
    {
        if ($this->registrations->contains($lesson)) {
            $this->registrations->removeElement($lesson);
            // set the owning side to null (unless already changed)
            if ($lesson->getLesson() === $this) {
                $lesson->setLesson(null);
            }
        }

        return $this;
    }

    public function getTraining(): ?training
    {
        return $this->training;
    }

    public function setTraining(?training $training): self
    {
        $this->training = $training;

        return $this;
    }

    public function getLocationId(): ?Location
    {
        return $this->location;
    }

    public function setLocationId(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }
}
