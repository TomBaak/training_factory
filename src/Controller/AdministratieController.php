<?php
	
	
	namespace App\Controller;
	
	use App\Entity\Lesson;
	use App\Entity\Person;
	use App\Entity\Registration;
	use App\Entity\Training;
	use App\Form\PersonInstructeurType;
	use App\Form\PersonType;
	use App\Form\TrainingType;
	use DateTime;
	use Doctrine\ORM\EntityManagerInterface;
	use MongoDB\Driver\Session;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
	
	class AdministratieController extends AbstractController
	{
		private $session;
		
		private $passwordEncoder;
		
		public function __construct(UserPasswordEncoderInterface $passwordEncoder, SessionInterface $session)
		{
			$this->session = $session;
			$this->passwordEncoder = $passwordEncoder;
		}
		
		/**
		 * @Route("/administratie", name="administratie")
		 */
		public function trainer()
		{
			$user = $this->getDoctrine()->getRepository(Person::class)->findOneBy(array('loginname' => $this->session->get('user')->getLoginname()));
			
			return $this->render('administratie/home.html.twig', [
				
				'name' => $user->getFirstname(),
			
			]);
		}
		
		/**
		 * @Route("/administratie/training/trainingen", name="administratie_trainingen")
		 */
		public function trainerTrainingen()
		{
			$trainingen = $this->getDoctrine()->getRepository(Training::class)->findAll();
			
			
			return $this->render('bezoeker/trainingsAanbod.html.twig', [
				
				'trainings' => $trainingen,
			
			
			]);
		}
		
		//		TODO: Make confirmation messages
		
		/**
		 * @Route("/administratie/training/new", name="administratie_newTraining")
		 */
		public function newTraining(Request $request, EntityManagerInterface $em,SessionInterface $session)
		{
			$form = $this->createForm(TrainingType::class);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid()){
				$training = $form->getData();
				
				$em->persist($training);
				$em->flush();
				
				$session->getFlashBag()->add(
					'success',
					'Training aangemaakt'
				);
				
				return $this->redirectToRoute('administratie_trainingen');
			}
			
			return $this->render('administratie\trainingNew.html.twig',
				['trainingForm' => $form->createView()]);
		}
		
		/**
		 * @Route("administratie/training/edit/{id}", name="edit_training")
		 */
		public function updateTraining(Training $training, $id, Request $request, EntityManagerInterface $em, SessionInterface $session){
			
			$training_current = $this->getDoctrine()->getRepository(Training::class)->findOneBy(array('id' => $id));
			
			if($training == NULL){
				return $this->redirectToRoute('trainer_trainingen');
			}
			
			$form = $this->createForm(TrainingType::class, $training);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid()){
				$training = $form->getData();
				
				$em->persist($training);
				$em->flush();
				
				$session->getFlashBag()->add(
					'success',
					'Training aangepast'
				);
				
				return $this->redirectToRoute('administratie_trainingen');
			}
			
			return $this->render('administratie/trainingEdit.html.twig', [
				
				'training_current' => $training,
				'trainingForm' => $form->createView(),
			
			
			]);
		}
		
		/**
		 * @Route("administratie/training/remove/{id}", name="delete_training")
		 */
		public function deleteTraining($id, EntityManagerInterface $em, SessionInterface $session){
			
			$training = $this->getDoctrine()->getRepository(Training::class)->findOneBy(array('id' => $id));
			
			$em->remove($training);
			$em->flush();
			
			$session->getFlashBag()->add(
				'success',
				'Training verwijderd'
			);
			
			return $this->redirectToRoute('administratie_trainingen');
			
			
		}
		
		/**
		 * @Route("administratie/leden", name="leden")
		 */
		public function leden(EntityManagerInterface $em){
			
			$result = $this->getDoctrine()->getRepository(Person::class)->findAll();
			
			$leden = [];
			
			foreach($result as $person){
				
				if(array_search("ROLE_TRAINER" ,$person->getRoles()) === false && array_search("ROLE_ADMIN" ,$person->getRoles()) === false){
					array_push($leden, $person);
				}
				
			}
			
			return $this->render('administratie/leden.html.twig',[
				
				'leden' => $leden
				
			]);
			
			
		}
		
		/**
		 * @Route("administratie/instructeurs", name="instructeurs")
		 */
		public function instructeurs(EntityManagerInterface $em){
			
			$result = $this->getDoctrine()->getRepository(Person::class)->findAll();
			
			$employees = [];
			
			foreach($result as $person){
				
				if(array_search("ROLE_TRAINER" ,$person->getRoles()) !== false){
					array_push($employees, $person);
				}
				
			}
			
			return $this->render('administratie/instructeurs.html.twig',[
				
				'leden' => $employees
			
			]);
			
			
		}
		
		/**
		 * @Route("administratie/lessen", name="lessenPerson")
		 */
		public function lessenPerson(EntityManagerInterface $em, Request $request){
			
			$registered_lessons = $this->getDoctrine()->getRepository(Registration::class)->findBy(array('member' => $request->get('id')));

			
			$person = $this->getDoctrine()->getRepository(Person::class)->findOneBy(array('id' => $request->get('id')));
			
			return $this->render('administratie\lessen.html.twig', [
				
				'person' => $person,
				'registrations' => $registered_lessons
			
			]);
			
			
		}
		
		/**
		 * @Route("administratie/lessenEmployee", name="lessenEmployee")
		 */
		public function lessenEmployee(EntityManagerInterface $em, Request $request){

			$registered_lessons = $this->getDoctrine()->getRepository(Lesson::class)->findBy(array('instructor' => $request->get('id')));
			
			$person = $this->getDoctrine()->getRepository(Person::class)->findOneBy(array('id' => $request->get('id')));
			
			return $this->render('administratie\lessenEmployee.html.twig', [
				
				'person' => $person,
				'lessons' => $registered_lessons
			
			]);
			
			
		}
		
		/**
		 * @Route("administratie/gebruikerNonActief", name="nonActiefToggle")
		 */
		public function nonActiefToggle(EntityManagerInterface $em, Request $request, SessionInterface $session){
			
			$person = $this->getDoctrine()->getRepository(Person::class)->findOneBy(array('id' => $request->get('id')));
			
			if($person->getIsDisabled()){
				$isDisabled = false;
				$session->getFlashBag()->add(
					'success',
					'Gebruiker op Actief gezet'
				);
			}else{
				$isDisabled = true;
				$session->getFlashBag()->add(
					'success',
					'Gebruiker op In-Actief gezet'
				);
			}
			
			$person->setIsDisabled($isDisabled);
			
			$em->persist($person);
			
			$em->flush();
			
			return $this->redirectToRoute($request->get('prev_page'));
			
		}
		
		/**
		 * @Route("administratie/gebruikerVerwijderen", name="gebruikerVerwijderen")
		 */
		public function gebruikerVerwijderen(EntityManagerInterface $em, Request $request, SessionInterface $session){
			
			$person = $this->getDoctrine()->getRepository(Person::class)->findOneBy(array('id' => $request->get('id')));
			
			$em->remove($person);
			
			$em->flush();
			
			$session->getFlashBag()->add(
				'success',
				'Account instructeur is verwijderd'
			);
			
			return $this->redirectToRoute('instructeurs');
		}
		
		/**
		 * @Route("administratie/omzet", name="omzet")
		 */
		public function test(EntityManagerInterface $em, Request $request, SessionInterface $session){
			
			$person = $this->getDoctrine()->getRepository(Person::class)->findOneBy(array('id' => $request->get('id')));
			
			return $this->render('administratie\omzet.html.twig', [
				
				'inst' => $person
			
			]);
			
		}
		
		/**
		 * @Route("administratie/register", name="registerInstructeur")
		 */
		public function register(Request $request, EntityManagerInterface $em, SessionInterface $session)
		{
			$form = $this->createForm(PersonInstructeurType::class);
			
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				$newPerson = $form->getData();
				
				$newPerson->setPassword($this->passwordEncoder->encodePassword(
					$newPerson,
					$newPerson->getPassword()
				));
				
				$newPerson->setRoles(["ROLE_TRAINER"]);
				$newPerson->setIsDisabled(0);
				$newPerson->setLoginname(str_replace(' ', '', strtolower($newPerson->getFirstname() . $newPerson->getLastname())));
				
				$em->persist($newPerson);
				$em->flush();
				
				$session->getFlashBag()->add(
					'success',
					'Instructeur geregistreerd'
				);
				
				
				return $this->redirectToRoute('instructeurs');
			}
			
			return $this->render('registration/register.html.twig', [
				
				'registrationForm' => $form->createView(),
			
			]);
			
		}
		
	}