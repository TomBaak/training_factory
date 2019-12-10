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
		
	}