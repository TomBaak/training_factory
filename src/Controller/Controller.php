<?php


    namespace App\Controller;

    use App\Entity\Person;
    use App\Entity\Training;
	use App\Form\PersonType;
	use Doctrine\ORM\EntityManagerInterface;
	use Doctrine\ORM\Mapping\Entity;
	use phpDocumentor\Reflection\Types\This;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\Session\Session;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

	class Controller extends AbstractController
    {
		private $passwordEncoder;
		
		private $session;
  
		public function __construct(UserPasswordEncoderInterface $passwordEncoder, SessionInterface $session)
		{
			
			$this->passwordEncoder = $passwordEncoder;
			$this->session  = new Session();
			
		}
    	
        /**
         * @Route("/", name="home")
         */
        public function home()
        {
            return $this->render('bezoeker/home.html.twig');
        }

        /**
         * @Route("/trainings_aanbod", name="trainingen")
         */
        public function trainingen()
        {

            $trainingen = $this->getDoctrine()->getRepository(Training::class)->findAll();


            return $this->render('bezoeker/trainingsAanbod.html.twig', [

                'trainings' => $trainingen,


            ]);
        }

        /**
         * @Route("/contact", name="contact")
         */
        public function contact()
        {
            return $this->render('bezoeker/locatieContact.html.twig');
        }
	
		/**
		 * @Route("/rules", name="rules")
		 */
		public function rules()
		{
			return $this->render('bezoeker/gedragsRegels.html.twig');
		}
	
		/**
		 * @Route("/noAccess", name="noAccess")
		 */
		public function noAccess()
		{
			return $this->render('noAccess.html.twig');
		}
	
		/**
		 * @Route("/register", name="register")
		 */
		public function register(Request $request, EntityManagerInterface $em)
		{
			$form = $this->createForm(PersonType::class);
			
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				$newPerson = $form->getData();
				
				$newPerson->setPassword($this->passwordEncoder->encodePassword(
					$newPerson,
					$newPerson->getPassword()
				));
				
				$newPerson->setIsDisabled(0);
				$newPerson->setLoginname(str_replace(' ', '', strtolower($newPerson->getFirstname() . $newPerson->getLastname())));
				
				$em->persist($newPerson);
				$em->flush();
				
				return $this->redirectToRoute('login');
			}
			
			return $this->render('registration/register.html.twig', [
			
				'registrationForm' => $form->createView(),
			
			]);
			
		}
		
		/**
		 * @Route("/passwordReset", name="resetPw")
		 */
		public function passwordReset(Request $request)
		{
			return $this->render('bezoeker/resetPw.html.twig');
		}
		
		public function setUser($email){
			$this->session->set('user', $this->getDoctrine()->getRepository(Person::class)->findOneBy(['emailaddress' => $email]));
		}

    }