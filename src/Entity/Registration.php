<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegistrationRepository")
 */
class Registration
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $payment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="registration")
     * @ORM\JoinColumn(nullable=false)
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\lesson", inversedBy="lesson")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lesson;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPayment(): ?string
    {
        return $this->payment;
    }

    public function setPayment(?string $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getMember(): ?Person
    {
        return $this->member;
    }

    public function setMember(?Person $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function getLesson(): ?lesson
    {
        return $this->lesson;
    }

    public function setLesson(?lesson $lesson): self
    {
        $this->lesson = $lesson;

        return $this;
    }
}
