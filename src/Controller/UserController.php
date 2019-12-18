<?php
	
	
	namespace App\Controller;
	
	
	use App\Entity\Lesson;
	use App\Entity\Person;
	use App\Entity\Registration;
	use App\Form\EmployeeEditType;
	use App\Form\LessonType;
	use App\Form\PersonEditType;
	use App\Form\PersonType;
	use DateInterval;
	use DatePeriod;
	use DateTime;
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
			
			if (in_array("ROLE_TRAINER", $this->getUser()->getRoles()) || in_array("ROLE_TRAINER", $this->getUser()->getRoles())) {
				$form = $this->createForm(EmployeeEditType::class, $person);
				$editTemplate = 'lid/editEmployeeProfile.html.twig';
			} else {
				$form = $this->createForm(PersonEditType::class, $person);
				$editTemplate = 'lid/editProfile.html.twig';
			}
			
			
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				$person = $form->getData();
				
				$em->persist($person);
				$em->flush();
				
				return $this->redirectToRoute('profile');
			}
			
			
			return $this->render($editTemplate, [
				
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
			
			$onlyAvailable = false;
			
			
			$today = $date = new DateTime(date("Y-m-d") ,new \DateTimeZone('Europe/Amsterdam'));
			
			$selected_date = new DateTime(date("Y-m-d", 01-01-01) ,new \DateTimeZone('Europe/Amsterdam'));;
			
			if ($request->query->get('date') != NULL) {
				$request_date = $request->query->get('date');
				$selected_date = new \DateTime($request_date, new \DateTimeZone('Europe/Amsterdam'));
				$lessen = $this->getDoctrine()->getRepository(Lesson::class)->findBy(['date' => $selected_date]);
				$subpage = true;
			} else {
				$lessen = $this->getDoctrine()->getRepository(Lesson::class)->findAll();
				$subpage = false;
			}
			
			$maxDates = 18;
			
			$dates = [];
			
//			if($onlyAvailable){
//				//Fills $date with the all the different dates of the lessen and makes sure there are no duplicate dates
//				foreach ($lessen as &$les) {
//
//					if (array_search($les->getDate(), $dates) === false) {
//						array_push($dates, $les->getDate());
//					}
//
//				}
//
//				usort($lessen, function ($a, $b) {
//					if ($a->getDate() == $b->getDate()) {
//						return 0;
//					}
//					return ($a->getDate() < $b->getDate()) ? -1 : 1;
//				});
//			}
			
			//generates array of dates with the amount of days ($maxDates) after today !!ONLY FOR THE DATE CHOOSING NOT FOR THE LESSONS THAT ARE DISPLAYED BY DEFAULT!!
			for ($i = 0; $i < $maxDates; $i++) {
				$date = new DateTime(date("Y-m-d") ,new \DateTimeZone('Europe/Amsterdam'));
				date_add($date, date_interval_create_from_date_string($i . 'day'));
				array_push($dates, $date);
			}
			
			return $this->render('lid/lessenAanbod.html.twig', [
				
				'user' => $this->getUser(),
				'lessen' => $lessen,
				'dates' => $dates,
				'subpage' => $subpage,
				'today_date' => $today,
				'curr_date' => $selected_date
			
			]);
		}
		
		/**
		 * @Route("lid/nieuweInschrijving/{id}", name="nieuweInschrijving")
		 */
		public function nieuweInschrijving($id, EntityManagerInterface $em, SessionInterface $session)
		{
			
			if ($this->getDoctrine()->getRepository(Lesson::class)->findOneBy(['id' => $id]) != NULL) {
				$registration = new Registration();
				
				$registration->setMember($this->getUser());
				$registration->setLesson($this->getDoctrine()->getRepository(Lesson::class)->findOneBy(['id' => $id]));
				$registration->setPayment(false);
				
				if ($this->getDoctrine()->getRepository(Registration::class)->findBy(array('member' => $this->getUser()->getId(), 'lesson' => $id)) == NULL) {
					$em->persist($registration);
					$em->flush();
					
					$session->getFlashBag()->add(
						'success',
						'U bent ingeschreven op de les'
					);
					
				} else {
					
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
		public function uitschrijvenOpLes($id, EntityManagerInterface $em, SessionInterface $session)
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
		public function inschrijvingen(EntityManagerInterface $em, SessionInterface $session)
		{
			
			$registered_lessons = $this->getDoctrine()->getRepository(Registration::class)->findBy(array('member' => $this->getUser()->getId()));
			
			return $this->render('lid\inschrijvingen.hmtl.twig', [
				
				'registrations' => $registered_lessons
			
			]);
			
			
		}
		
	}