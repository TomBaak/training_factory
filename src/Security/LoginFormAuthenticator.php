<?php

namespace App\Security;

use App\Repository\PersonRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;


class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
	private $userRepository;
	
	private $router;
	
	public function __construct(PersonRepository $personRepository, RouterInterface $router)
	{
		$this->userRepository = $personRepository;
		$this->router = $router;
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
		if($credentials['password'] == $user->getPassword()){
			return true;
		}else{
			return false;
		};
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // todo
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
		return new RedirectResponse($this->router->generate('home'));
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
		return new RedirectResponse($this->router->generate('noAccess'));
    }

    public function supportsRememberMe()
    {
        // todo
    }
	
	/**
	 * Return the URL to the login page.
	 *
	 * @return string
	 */
	protected function getLoginUrl()
	{
		return $this->router->generate('login');
	}
}
