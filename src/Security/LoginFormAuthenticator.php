<?php
	
	namespace App\Security;
	
	use App\Entity\Lesson;
	use App\Repository\PersonRepository;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Routing\RouterInterface;
	use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
	use Symfony\Component\Security\Core\Exception\AuthenticationException;
	use Symfony\Component\Security\Core\User\UserInterface;
	use Symfony\Component\Security\Core\User\UserProviderInterface;
	use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
	use Symfony\Component\Security\Http\Util\TargetPathTrait;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Doctrine\ORM\EntityManagerInterface;
	
	
	class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
	{
		use TargetPathTrait;
		
		private $userRepository;
		
		private $router;
		
		private $passwordEncoder;
		
		private $session;
		
		public function __construct(PersonRepository $personRepository, RouterInterface $router, UserPasswordEncoderInterface $passwordEncoder, SessionInterface $session)
		{
			$this->userRepository = $personRepository;
			$this->router = $router;
			$this->passwordEncoder = $passwordEncoder;
			$this->session = $session;
		}
		
		public function supports(Request $request)
		{
			return $request->attributes->get('_route') === 'login'
				&& $request->isMethod('POST');
		}
		
		public function getCredentials(Request $request)
		{
			return [
				'email' => $request->request->get('email'),
				'password' => $request->request->get('password'),
			];
		}
		
		public function getUser($credentials, UserProviderInterface $userProvider)
		{
			return $this->userRepository->findOneBy(['emailaddress' => $credentials['email']]);
		}
		
		public function checkCredentials($credentials, UserInterface $user)
		{
				return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
		}
		
		public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
		{
			
			
			if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
				return new RedirectResponse($targetPath);
			}
			
			return new RedirectResponse($this->router->generate('home'));
		}
		
		protected function getLoginUrl()
		{
			return $this->router->generate('login');
		}
		
		public function start(Request $request, AuthenticationException $authException = null)
		{
			return new RedirectResponse($this->router->generate('login'));
		}
		
		public function supportsRememberMe()
		{
		}
	}
