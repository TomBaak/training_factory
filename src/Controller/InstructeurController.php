<?php
	
	
	namespace App\Controller;
	
	
	use App\Entity\Lesson;
	use App\Entity\Person;
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
				$lessen = $this->getDoctrine()->getRepository(Lesson::class)->findBy(['date' => new \DateTime($request->query->get('date'), new \DateTimeZone('Europe/Amsterdam'))]);
				$subpage = true;
			} else {
				$lessen = $this->getDoctrine()->getRepository(Lesson::class)->findAll();
				$subpage = false;
			}
			
			$dates = [];
			
			//Fills $date with the all the different dates of the lessen and makes sure there are no duplicate dates
			foreach ($lessen as &$les) {
				
				if (array_search($les->getDate(), $dates) === false) {
					array_push($dates, $les->getDate());
				}
				
			}
			
			return $this->render('lid/lessenAanbod.html.twig', [
				
				'user' => $this->getUser(),
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
	}