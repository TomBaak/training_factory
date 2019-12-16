<?php
	
	
	namespace App\Controller;
	
	
	use App\Entity\Lesson;
	use App\Entity\Person;
	use App\Entity\Registration;
	use App\Form\LessonType;
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
			
			if ($request->query->get('date') != NULL) {
				$lessen = $this->getDoctrine()->getRepository(Lesson::class)->findBy([
					
					'date' => new \DateTime($request->query->get('date'), new \DateTimeZone('Europe/Amsterdam')),
					'instructor' => $this->getUser()->getId()
				
				]);
				$subpage = true;
			} else {
				$lessen = $this->getDoctrine()->getRepository(Lesson::class)->findBy([
					
					'instructor' => $this->getUser()->getId()
				
				]);
				$subpage = false;
			}
			
			$dates = [];
			
			usort($lessen, function ($a, $b) {
				if ($a->getDate() == $b->getDate()) {
					return 0;
				}
				return ($a->getDate() < $b->getDate()) ? -1 : 1;
			});
			
			//Fills $date with the all the different dates of the lessen and makes sure there are no duplicate dates
			foreach ($lessen as &$les) {
				
				if (array_search($les->getDate(), $dates) === false) {
					array_push($dates, $les->getDate());
				}
				
			}
			
			return $this->render('lid/lessenAanbod.html.twig', [
				
				'lessen' => $lessen,
				'dates' => $dates,
				'subpage' => $subpage
			
			]);
		}
		
		/**
		 * @Route("/instructeur/lessen/new", name="instructeur_lessen_new")
		 */
		public function instructeurLessenNew(Request $request, EntityManagerInterface $em)
		{
			$form = $this->createForm(LessonType::class);
			
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				$lesson = $form->getData();
				
				$lesson->setInstructor($this->getUser());
				
				$em->persist($lesson);
				$em->flush();
				
				return $this->redirectToRoute('instructeur_lessen');
			}
			
			return $this->render('instructeur\lessonNew.html.twig',
				['lessonForm' => $form->createView()]);
		}
		
		/**
		 * @Route("/instructeur/lessen/edit/{id}", name="edit_lesson")
		 */
		public function updateTraining(Lesson $lesson, $id, Request $request, EntityManagerInterface $em)
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
		public function deleteTraining($id, EntityManagerInterface $em)
		{
			
			$lesson = $this->getDoctrine()->getRepository(Lesson::class)->findOneBy(array('id' => $id));
			
			$em->remove($lesson);
			$em->flush();
			
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