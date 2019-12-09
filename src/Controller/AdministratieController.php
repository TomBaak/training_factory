<?php
	
	
	namespace App\Controller;
	
	use App\Entity\Person;
	use App\Entity\Training;
	use App\Form\TrainingType;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Symfony\Component\Routing\Annotation\Route;
	
	class AdministratieController extends AbstractController
	{
		private $session;
		
		public function __construct(SessionInterface $session)
		{
			$this->session = $session;
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
		 * @Route("/administratie/training/overview", name="administratie_trainingen")
		 */
		public function trainerTrainingen()
		{
			$trainingen = $this->getDoctrine()->getRepository(Training::class)->findAll();
			
			
			return $this->render('administratie/trainingsAanbod.html.twig', [
				
				'trainings' => $trainingen,
			
			
			]);
		}
		
		//		TODO: Make confirmation messages
		
		/**
		 * @Route("/administratie/training/new", name="administratie_newTraining")
		 */
		public function newTraining(Request $request, EntityManagerInterface $em)
		{
			$form = $this->createForm(TrainingType::class);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid()){
				$training = $form->getData();
				
				$em->persist($training);
				$em->flush();
				
				return $this->redirectToRoute('administratie_trainingen');
			}
			
			return $this->render('administratie\trainingNew.html.twig',
				['trainingForm' => $form->createView()]);
		}
		
		/**
		 * @Route("administratie/training/edit/{id}", name="edit_training")
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
		public function deleteTraining($id, EntityManagerInterface $em){
			
			$training = $this->getDoctrine()->getRepository(Training::class)->findOneBy(array('id' => $id));
			
			$em->remove($training);
			$em->flush();
			
			return $this->redirectToRoute('administratie_trainingen');
			
			
		}
		
	}