<?php
	
	
	namespace App\Controller;
	
	use App\Entity\Person;
	use App\Entity\Training;
	use App\Form\TrainingType;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	
	class TrainerController extends AbstractController
	{
		
		/**
		 * @Route("/trainer", name="trainer")
		 */
		public function trainer()
		{
			return $this->render('trainer/home.html.twig');
		}
		
		/**
		 * @Route("/trainer/training/overview", name="trainer_trainingen")
		 */
		public function trainerTrainingen()
		{
			$trainingen = $this->getDoctrine()->getRepository(Training::class)->findAll();
			
			
			return $this->render('trainer/trainingsAanbod.html.twig', [
				
				'trainings' => $trainingen,
			
			
			]);
		}
		
		/**
		 * @Route("/trainer/training/new", name="trainer_newTraining")
		 */
		public function newTraining(Request $request, EntityManagerInterface $em)
		{
			$form = $this->createForm(TrainingType::class);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid()){
				$training = $form->getData();
				
				$em->persist($training);
				$em->flush();
				
				return $this->redirectToRoute('trainer_trainingen');
			}
			
			return $this->render('trainer\trainingNew.html.twig',
				['trainingForm' => $form->createView()]);
		}
		
		/**
		 * @Route("trainer/training/edit/{id}", name="edit_training")
		 */
		public function updateTraining(Training $training, $id, Request $request, EntityManagerInterface $em){
			
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
				
				return $this->redirectToRoute('trainer_trainingen');
			}
			
			return $this->render('trainer/trainingEdit.html.twig', [
				
				'training_current' => $training,
				'trainingForm' => $form->createView(),
			
			
			]);
		}
		
		/**
		 * @Route("trainer/training/remove/{id}", name="delete_training")
		 */
		public function deleteTraining($id, EntityManagerInterface $em){
			
			$training = $this->getDoctrine()->getRepository(Training::class)->findOneBy(array('id' => $id));
			
			$em->remove($training);
			$em->flush();
			
			return $this->redirectToRoute('trainer_trainingen');
			
			
		}
		
	}