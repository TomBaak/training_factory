<?php
	
	namespace App\Controller;
	
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Session\Session;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
	use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
	
	class SercurityController extends AbstractController
	{
		private $session;
		
		public function __construct(SessionInterface $session)
		{
			$this->session = new Session();;
		}
		
		/**
		 * @Route("/login", name="login")
		 */
		public function login(Request $request, AuthenticationUtils $authenticationUtils)
		{
			
			if ($this->session->get('user') != NULL) {
				return $this->redirectToRoute('profile');
			}
			
			// get the login error if there is one
			$error = $authenticationUtils->getLastAuthenticationError();
			
			// last username entered by the user
			$lastUsername = $authenticationUtils->getLastUsername();
			
			//TODO: Fix lastusername not being passed
			
			return $this->render('sercurity/login.html.twig', [
				'last_username' => $lastUsername,
				'error' => $error,
			]);
		}
		
		/**
		 * @Route("/logout", name="logout")
		 */
		public function logout()
		{
		
		}
	}
