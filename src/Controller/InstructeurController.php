<?php
	
	
	namespace App\Controller;
	
	
	use App\Entity\Lesson;
	use App\Entity\Person;
	use App\Entity\Registration;
	use App\Form\LessonType;
	use DateTime;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Symfony\Component\Routing\Annotation\Route;
	
	class InstructeurController extends AbstractController
	{
		private $session;
		
		public function __construct(SessionInterface $session)
		{
			$this->session = $session;
		}
		
		/**
		 * @Route("/instructeur", name="instructeur")
		 */
		public function instructeur()
		{
			$user = $this->getDoctrine()->getRepository(Person::class)->findOneBy(array('loginname' => $this->session->get('user')->getLoginname()));
			
			return $this->render('instructeur/home.html.twig', [
				
				'name' => $user->getFirstname(),
			
			]);
		}
		
		/**
		 * @Route("/instructeur/lessen", name="instructeur_lessen")
		 */
		public function instructeurLessen(Request $request)
		{
			//TODO: Catch wrong GET

			$lessen = [];
			
			$subpage = false;
			
			$request_date = 0;
			
			$onlyAvailable = false;
			
			$today = $date = new DateTime(date("Y-m-d") ,new \DateTimeZone('Europe/Amsterdam'));
			
			$selected_date = new DateTime(date("Y-m-d", 01-01-01) ,new \DateTimeZone('Europe/Amsterdam'));;
			
			if ($request->query->get('date') != NULL) {
				$request_date = $request->query->get('date');
				$selected_date = new \DateTime($request_date, new \DateTimeZone('Europe/Amsterdam'));
				$lessen = $this->getDoctrine()->getRepository(Lesson::class)->findBy(['date' => $selected_date, 'instructor' => $this->getUser()->getId()]);
				$subpage = true;
			} else {
				$lessen = $this->getDoctrine()->getRepository(Lesson::class)->findBy(['instructor' => $this->getUser()->getId()]);
				$subpage = false;
			}
			
			$maxDates = 18;
			
			$dates = [];
			
			//generates array of dates with the amount of days ($maxDates) after today !!ONLY FOR THE DATE CHOOSING NOT FOR THE LESSONS THAT ARE DISPLAYED BY DEFAULT!!
			for ($i = 0; $i < $maxDates; $i++) {
				$date = new DateTime(date("Y-m-d") ,new \DateTimeZone('Europe/Amsterdam'));
				date_add($date, date_interval_create_from_date_string($i . 'day'));
				array_push($dates, $date);
			}
			
			//TODO: Bug lessons arent sorted
			
			return $this->render('lid/lessenAanbod.html.twig', [
				
				'lessen' => $lessen,
				'dates' => $dates,
				'subpage' => $subpage,
				'today_date' => $today,
				'curr_date' => $selected_date
			
			]);
		}
		
		/**
		 * @Route("/instructeur/lessen/new", name="instructeur_lessen_new")
		 */
		public function instructeurLessenNew(Request $request, EntityManagerInterface $em, SessionInterface $session)
		{
			$form = $this->createForm(LessonType::class);
			
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				$lesson = $form->getData();
				
				$lesson->setInstructor($this->getUser());
				
				$em->persist($lesson);
				$em->flush();
				
				$session->getFlashBag()->add(
					'success',
					'Les aangemaakt'
				);
				
				return $this->redirectToRoute('instructeur_lessen');
			}
			
			return $this->render('instructeur\lessonNew.html.twig',
				['lessonForm' => $form->createView()]);
		}
		
		/**
		 * @Route("/instructeur/lessen/edit/{id}", name="edit_lesson")
		 */
		public function updateTraining(Lesson $lesson, $id, Request $request, EntityManagerInterface $em, SessionInterface $session)
		{
			
			$lesson_current = $this->getDoctrine()->getRepository(Lesson::class)->findOneBy(array('id' => $id));
			
			if ($lesson == NULL) {
				return $this->redirectToRoute('instructeur_lessen');
			}
			
			$form = $this->createForm(LessonType::class, $lesson);
			
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				$lesson = $form->getData();
				
				$em->persist($lesson);
				$em->flush();
				
				$session->getFlashBag()->add(
					'success',
					'Les aangepast'
				);
				
				return $this->redirectToRoute('instructeur_lessen');
			}
			
			return $this->render('instructeur/lessonEdit.html.twig', [
				
				'lesson_current' => $lesson_current,
				'lessonForm' => $form->createView(),
			
			
			]);
		}
		
		/**
		 * @Route("/instructeur/lessen/remove/{id}", name="delete_lesson")
		 */
		public function deleteTraining($id, EntityManagerInterface $em, SessionInterface $session)
		{
			
			$lesson = $this->getDoctrine()->getRepository(Lesson::class)->findOneBy(array('id' => $id));
			
			$em->remove($lesson);
			$em->flush();
			
			$session->getFlashBag()->add(
				'success',
				'Les verwijderd'
			);
			
			return $this->redirectToRoute('instructeur_lessen');
			
			
		}
		
		/**
		 * @Route("/instructeur/lessen/deelnemers/{id}", name="deelnemers")
		 */
		public function deelnemers($id, EntityManagerInterface $em, SessionInterface $session)
		{
			$lesson = $this->getDoctrine()->getRepository(Lesson::class)->findOneBy(array('id' => $id));
			
			$this->session->set('lastId', $id);
			
			return $this->render('instructeur/deelnemers.html.twig', [
			
				'lesson' => $lesson
			
			]);
		}
		
		/**
		 * @Route("/instructeur/lessen/deelnemers/payed/{id}", name="deelnemerPayed")
		 */
		public function deelnemersPayed($id, EntityManagerInterface $em, SessionInterface $session)
		{
			
			$registration = $this->getDoctrine()->getRepository(Registration::class)->findOneBy(array('id' => $id));
			
			if($registration != NULL){
				
				$registration->setPayment(true);
				
				$em->persist($registration);
				$em->flush();
				
				$session->getFlashBag()->add(
					'success',
					'Betaling reregistreerd'
				);
				
			}else{
				$session->getFlashBag()->add(
					'error',
					'Fout, probeer opnieuw'
				);
			}
			
			return $this->redirectToRoute('deelnemers', ['id' => $this->session->get('lastId')]);
			
		}
	}