<?php
	
	
	namespace App\Controller;
	
	use App\Entity\Person;
	use App\Entity\Training;
	use App\Forms\TrainingType;
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
		 * @Route("/trainer/trainingen", name="trainer_trainingen")
		 */
		public function trainerTrainingen()
		{
			$trainingen = $this->getDoctrine()->getRepository(Training::class)->findAll();
			
			
			return $this->render('trainer/trainingsAanbod.html.twig', [
				
				'trainings' => $trainingen,
			
			
			]);
		}
		
		/**
		 * @Route("/trainer/newTraining", name="trainer_newTraining")
		 */
		public function newTraining(Request $request)
		{
			$form = $this->createForm(TrainingType::class);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid()){
				$training = $form->getData();
				$em=$this->getDoctrine()->getManager();
				$em->persist($training);
				$em->flush();
				
				return $this->redirectToRoute('trainer/trainingen');
			}
			
			return $this->render('admin/nieuweTraining.html.twig',
				['trainingForm' => $form->createView()]);
		}
		
	}