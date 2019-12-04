<?php
	
	
	namespace App\Controller;
	
	
	use App\Entity\Lesson;
	use App\Form\LessonType;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	
	class InstructeurController extends AbstractController
	{
		/**
		 * @Route("/instructeur", name="instructeur")
		 */
		public function instructeur()
		{
			return $this->render('instructeur/home.html.twig');
		}
		
		/**
		 * @Route("/instructeur/lessen", name="instructeur_lessen")
		 */
		public function instructeurLessen()
		{
			$lessen = $this->getDoctrine()->getRepository(Lesson::class)->findAll();
			
			return $this->render('instructeur/instructeurLessen.html.twig', [
				
				'lessen' => $lessen,
			
			
			]);
		}
		
		/**
		 * @Route("/instructeur/lessen/new", name="instructeur_lessen_new")
		 */
		public function instructeurLessenNew(Request $request, EntityManagerInterface $em)
		{
			$form = $this->createForm(LessonType::class);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid()){
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
		public function updateTraining(Lesson $lesson, $id, Request $request, EntityManagerInterface $em){
			
			$lesson_current = $this->getDoctrine()->getRepository(Lesson::class)->findOneBy(array('id' => $id));
			
			if($lesson == NULL){
				return $this->redirectToRoute('instructeur_lessen');
			}
			
			$form = $this->createForm(LessonType::class, $lesson);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid()){
				$training = $form->getData();
				
				$em->persist($training);
				$em->flush();
				
				return $this->redirectToRoute('instructeur_lessen');
			}
			
			return $this->render('administratie/trainingEdit.html.twig', [
				
				'lesson_current' => $lesson_current,
				'Lesson_Form' => $form->createView(),
			
			
			]);
		}
	}