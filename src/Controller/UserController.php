<?php
	
	
	namespace App\Controller;
	
	
	use App\Entity\Lesson;
	use App\Entity\Person;
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
			$this->session  = new Session();
		}
		
		/**
		 * @Route("lid/profiel", name="profile")
		 */
		public function home()
		{
			dump($this->getUser());
			dump($this->getUser()->getRoles());
			
			return $this->render('lid/profile.html.twig', [
				
				'user' => $this->getUser()
				
			]);
		}
		
		/**
		 * @Route("lid/profiel/edit/{id}", name="profileEdit")
		 */
		public function editProfile($id,Request $request, EntityManagerInterface $em,Person $person)
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
			
			if($request->query->get('date') != NULL){
				$lessen = $this->getDoctrine()->getRepository(Lesson::class)->findBy(['date' => new \DateTime($request->query->get('date'), new \DateTimeZone('Europe/Amsterdam'))]);
				$subpage = true;
			}else{
				$lessen = $this->getDoctrine()->getRepository(Lesson::class)->findAll();
				$subpage = false;
			}
			
			$dates = [];
			
			//Fills $date with the all the different dates of the lessen and makes sure there are no duplicate dates
			foreach($lessen as &$les){
				
				if(array_search($les->getDate() , $dates) === false){
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
		
	}