<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="string", length=255)
     */
    private $location;

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
    private $lesson;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Training", inversedBy="training")
     * @ORM\JoinColumn(nullable=false)
     */
    private $training;

    public function __construct()
    {
        $this->lesson = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): string
    {
        return $this->time->format('H:i');
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getDate(): string
    {
        return $this->date->format('Y-m-d');
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
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
    public function getLesson(): Collection
    {
        return $this->lesson;
    }

    public function addLesson(Registration $lesson): self
    {
        if (!$this->lesson->contains($lesson)) {
            $this->lesson[] = $lesson;
            $lesson->setLesson($this);
        }

        return $this;
    }

    public function removeLesson(Registration $lesson): self
    {
        if ($this->lesson->contains($lesson)) {
            $this->lesson->removeElement($lesson);
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
}
