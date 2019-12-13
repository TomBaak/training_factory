<?php
	
	namespace App\DataFixtures;
	
	use App\Entity\Person;
	use Doctrine\Bundle\FixturesBundle\Fixture;
	use Doctrine\Common\Persistence\ObjectManager;
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
	
	class PersonFixtures extends Fixture
	{
		private $passwordEncoder;
		
		public function __construct(UserPasswordEncoderInterface $passwordEncoder)
		{
			
			$this->passwordEncoder = $passwordEncoder;
			
		}
		
		public function load(ObjectManager $manager)
		{
			$person = new Person();
			
			$person->setLoginname('admin');
			$person->setFirstname('admin');
			$person->setPreprovision('mr.');
			$person->setLastname('admin');
			$person->setDateofbirth(new \DateTime('20:00'));
			$person->setGender(1);
			$person->setEmailaddress('admin@admin.com');
			
			$person->setPassword($this->passwordEncoder->encodePassword(
				$person,
				'sleutel'
			));
			
			$person->setRoles([
				'ROLE_ADMIN',
				'ROLE_TRAINER'
			]);
			
			$manager->persist($person);
			
			$manager->flush();
		}
	}
