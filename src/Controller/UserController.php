<?php
	
	
	namespace App\Controller;
	
	
	use App\Entity\Lesson;
	use App\Entity\Person;
	use App\Entity\Registration;
	use App\Form\LessonType;
	use App\Form\PersonEditType;
	use App\Form\PersonType;
	use Doctrine\ORM\EntityManagerInterface;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Session\Session;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	
	class UserController extends AbstractController
	{
		//Controller for logged in users
		
		private $session;
		
		public function __construct(SessionInterface $session)
		{
			$this->session = new Session();
		}
		
		/**
		 * @Route("lid/profiel", name="profile")
		 */
		public function profile()
		{
			$registered_lessons = $this->getDoctrine()->getRepository(Registration::class)->findBy(array('member' => $this->getUser()->getId()));
			
			return $this->render('lid/profile.html.twig', [
				
				'user' => $this->getUser(),
				'lessons' => $registered_lessons
			
			]);
		}
		
		/**
		 * @Route("lid/profiel/edit/{id}", name="profileEdit")
		 */
		public function editProfile($id, Request $request, EntityManagerInterface $em, Person $person)
		{
			$person_current = $this->getDoctrine()->getRepository(Person::class)->findOneBy(array('id' => $id));
			
			if ($person == NULL) {
				return $this->redirectToRoute('profile');
			}
			
			$form = $this->createForm(PersonEditType::class, $person);
			
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				$person = $form->getData();
				
				$em->persist($person);
				$em->flush();
				
				return $this->redirectToRoute('profile');
			}
			
			return $this->render('lid/editProfile.html.twig', [
				
				'personCurrent' => $person_current,
				'registrationForm' => $form->createView(),
			
			]);
		}
		
		/**
		 * @Route("lid/inschrijvenOpLes", name="inschrijvenOpLes")
		 */
		public function inschrijvenOpLes(Request $request)
		{
			//TODO: Catch wrong GET
//
			$lessen = [];
			
			$subpage = false;
			
			$request_date = 0;
			
			if ($request->query->get('date') != NULL) {
				$request_date = $request->query->get('date');
				$lessen = $this->getDoctrine()->getRepository(Lesson::class)->findBy(['date' => new \DateTime($request_date, new \DateTimeZone('Europe/Amsterdam'))]);
				$subpage = true;
			} else {
				$lessen = $this->getDoctrine()->getRepository(Lesson::class)->findAll();
				$subpage = false;
			}
			
			$dates = [];
			
			$maxDates = 3;
			
			//Fills $date with the all the different dates of the lessen and makes sure there are no duplicate dates
			foreach ($lessen as &$les) {
				
				if (array_search($les->getDate(), $dates) === false) {
					array_push($dates, $les->getDate());
				}
				
			}
			
			usort($lessen, function ($a, $b) {
				if ($a->getDate() == $b->getDate()) {
					return 0;
				}
				return ($a->getDate() < $b->getDate()) ? -1 : 1;
			});
			
			return $this->render('lid/lessenAanbod.html.twig', [
				
				'user' => $this->getUser(),
				'lessen' => $lessen,
				'dates' => $dates,
				'subpage' => $subpage,
				'curr_date' => $request_date
			
			]);
		}
		
		/**
		 * @Route("lid/nieuweInschrijving/{id}", name="nieuweInschrijving")
		 */
		public function nieuweInschrijving($id, EntityManagerInterface $em,SessionInterface $session)
		{
			
			if ($this->getDoctrine()->getRepository(Lesson::class)->findOneBy(['id' => $id]) != NULL) {
				$registration = new Registration();
				
				$registration->setMember($this->getUser());
				$registration->setLesson($this->getDoctrine()->getRepository(Lesson::class)->findOneBy(['id' => $id]));
				$registration->setPayment(false);
				
				if($this->getDoctrine()->getRepository(Registration::class)->findBy(array('member' => $this->getUser()->getId(), 'lesson' => $id)) == NULL){
					$em->persist($registration);
					$em->flush();
					
					$session->getFlashBag()->add(
						'success',
						'U bent ingeschreven op de les'
					);
					
				}else{
					
					$session->getFlashBag()->add(
						'error',
						'U bent al ingeschreven op deze les!'
					);
					
					return $this->redirectToRoute('inschrijvenOpLes');
				}
				
				
				
				
				
				return $this->redirectToRoute('inschrijvenOpLes');
			}
			
			
		}
	
		/**
		 * @Route("lid/uitschrijvenOpLes/{id}", name="uitschrijvenOpLes")
		 */
		public function uitschrijvenOpLes($id, EntityManagerInterface $em,SessionInterface $session)
		{
			
			$registration = $this->getDoctrine()->getRepository(Registration::class)->findOneBy(['id' => $id]);
			
			if ($registration != NULL) {
				
				$session->getFlashBag()->add(
					'success',
					'U bent uitgeschreven op de les'
				);
				
				$em->remove($registration);
				$em->flush();
				
				return $this->redirectToRoute('inschrijvingen');
			}
			
			
		}
		
		/**
		 * @Route("lid/inschrijvingen", name="inschrijvingen")
		 */
		public function inschrijvingen(EntityManagerInterface $em,SessionInterface $session)
		{
			
			$registered_lessons = $this->getDoctrine()->getRepository(Registration::class)->findBy(array('member' => $this->getUser()->getId()));
			
			return $this->render('lid\inschrijvingen.hmtl.twig', [
				
				'registrations' => $registered_lessons
			
			]);
			
			
		}
		
	}