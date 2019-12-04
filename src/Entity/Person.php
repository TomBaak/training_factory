<?php
	
	namespace App\Entity;
	
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
	use Symfony\Component\Security\Core\User\UserInterface;
	
	/**
	 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
	 * @UniqueEntity(fields={"loginname"}, message="There is already an account with this loginname")
	 */
	class Person implements UserInterface, \Serializable
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
		private $loginname;
		
		/**
		 * @ORM\Column(type="string", length=255)
		 */
		private $password;
		
		/**
		 * @ORM\Column(type="string", length=255)
		 */
		private $firstname;
		
		/**
		 * @ORM\Column(type="string", length=255)
		 */
		private $preprovision;
		
		/**
		 * @ORM\Column(type="string", length=255)
		 */
		private $lastname;
		
		/**
		 * @ORM\Column(type="date")
		 */
		private $dateofbirth;
		
		/**
		 * @ORM\Column(type="boolean")
		 */
		private $gender;
		
		/**
		 * @ORM\Column(type="string", length=255)
		 */
		private $emailaddress;
		
		/**
		 * @ORM\Column(type="date", nullable=true)
		 */
		private $hiring_date;
		
		/**
		 * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
		 */
		private $salary;
		
		/**
		 * @ORM\Column(type="string", length=255, nullable=true)
		 */
		private $street;
		
		/**
		 * @ORM\Column(type="string", length=50, nullable=true)
		 */
		private $postalcode;
		
		/**
		 * @ORM\Column(type="string", length=255, nullable=true)
		 */
		private $place;
		
		/**
		 * @ORM\Column(type="json")
		 */
		private $roles = [];
		
		/**
		 * @ORM\OneToMany(targetEntity="App\Entity\lesson", mappedBy="instructor")
		 */
		private $lesson;
		
		/**
		 * @ORM\OneToMany(targetEntity="App\Entity\Registration", mappedBy="member")
		 */
		private $registration;
		
		public function __construct()
		{
			$this->lesson = new ArrayCollection();
			$this->registration = new ArrayCollection();
		}
		
		public function getId(): ?int
		{
			return $this->id;
		}
		
		public function getLoginname(): ?string
		{
			return $this->loginname;
		}
		
		public function setLoginname(string $loginname): self
		{
			$this->loginname = $loginname;
			
			return $this;
		}
		
		public function getPassword(): ?string
		{
			return $this->password;
		}
		
		public function setPassword(string $password): self
		{
			$this->password = $password;
			
			return $this;
		}
		
		public function getFirstname(): ?string
		{
			return $this->firstname;
		}
		
		public function setFirstname(string $firstname): self
		{
			$this->firstname = $firstname;
			
			return $this;
		}
		
		public function getPreprovision(): ?string
		{
			return $this->preprovision;
		}
		
		public function setPreprovision(string $preprovision): self
		{
			$this->preprovision = $preprovision;
			
			return $this;
		}
		
		public function getLastname(): ?string
		{
			return $this->lastname;
		}
		
		public function setLastname(string $lastname): self
		{
			$this->lastname = $lastname;
			
			return $this;
		}
		
		public function getDateofbirth(): ?\DateTimeInterface
		{
			return $this->dateofbirth;
		}
		
		public function setDateofbirth(\DateTime $dateofbirth): self
		{
			$this->dateofbirth = $dateofbirth;
			
			return $this;
		}
		
		public function getGender(): ?bool
		{
			return $this->gender;
		}
		
		public function setGender(bool $gender): self
		{
			$this->gender = $gender;
			
			return $this;
		}
		
		public function getEmailaddress(): ?string
		{
			return $this->emailaddress;
		}
		
		public function setEmailaddress(string $emailaddress): self
		{
			$this->emailaddress = $emailaddress;
			
			return $this;
		}
		
		public function getHiringDate(): ?\DateTimeInterface
		{
			return $this->hiring_date;
		}
		
		public function setHiringDate(?\DateTimeInterface $hiring_date): self
		{
			$this->hiring_date = $hiring_date;
			
			return $this;
		}
		
		public function getSalary(): ?string
		{
			return $this->salary;
		}
		
		public function setSalary(?string $salary): self
		{
			$this->salary = $salary;
			
			return $this;
		}
		
		public function getStreet(): ?string
		{
			return $this->street;
		}
		
		public function setStreet(?string $street): self
		{
			$this->street = $street;
			
			return $this;
		}
		
		public function getPostalcode(): ?string
		{
			return $this->postalcode;
		}
		
		public function setPostalcode(?string $postalcode): self
		{
			$this->postalcode = $postalcode;
			
			return $this;
		}
		
		public function getPlace(): ?string
		{
			return $this->place;
		}
		
		public function setPlace(?string $place): self
		{
			$this->place = $place;
			
			return $this;
		}
		
		public function getRoles(): array
		{
			$roles = $this->roles;
			// guarantee every user at least has ROLE_USER
			$roles[] = 'ROLE_USER';
			
			return array_unique($roles);
		}
		
		public function getSalt()
		{
		
		}
		
		public function eraseCredentials()
		{
		
		}
		
		public function getUsername()
		{
			return $this->loginname;
		}
		
		/**
		 * @return Collection|lesson[]
		 */
		public function getLesson(): Collection
		{
			return $this->lesson;
		}
		
		public function addLesson(lesson $lesson): self
		{
			if (!$this->lesson->contains($lesson)) {
				$this->lesson[] = $lesson;
				$lesson->setInstructor($this);
			}
			
			return $this;
		}
		
		public function removeLesson(lesson $lesson): self
		{
			if ($this->lesson->contains($lesson)) {
				$this->lesson->removeElement($lesson);
				// set the owning side to null (unless already changed)
				if ($lesson->getInstructor() === $this) {
					$lesson->setInstructor(null);
				}
			}
			
			return $this;
		}
		
		/**
		 * @return Collection|registration[]
		 */
		public function getRegistration(): Collection
		{
			return $this->registration;
		}
		
		public function addRegistration(registration $registration): self
		{
			if (!$this->registration->contains($registration)) {
				$this->registration[] = $registration;
				$registration->setMember($this);
			}
			
			return $this;
		}
		
		public function removeRegistration(registration $registration): self
		{
			if ($this->registration->contains($registration)) {
				$this->registration->removeElement($registration);
				// set the owning side to null (unless already changed)
				if ($registration->getMember() === $this) {
					$registration->setMember(null);
				}
			}
			
			return $this;
		}
		
		/**
		 * String representation of object
		 * @link https://php.net/manual/en/serializable.serialize.php
		 * @return string the string representation of the object or null
		 * @since 5.1.0
		 */
		public function serialize()
		{
			return serialize([
				$this->id,
				$this->loginname,
				$this->password,
			]);
		}
		
		/**
		 * Constructs the object
		 * @link https://php.net/manual/en/serializable.unserialize.php
		 * @param string $serialized <p>
		 * The string representation of the object.
		 * </p>
		 * @return void
		 * @since 5.1.0
		 */
		public function unserialize($serialized)
		{
			list(
				$this->id,
				$this->loginname,
				$this->password,
				) = unserialize($serialized, ['allowed_classes => false']);
		}
	}
