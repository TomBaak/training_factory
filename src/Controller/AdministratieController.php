<?php
	
	
	namespace App\Controller;
	
	use App\Entity\Lesson;
	use App\Entity\Person;
	use App\Entity\Registration;
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
		
		
	}