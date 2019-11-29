<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SercurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $utils)
    {
    	$error = $utils->getLastAuthenticationError();
    	
    	$lastUsername = $utils->getLastUsername();
    	
        return $this->render('sercurity/login.html.twig', [
            'error' => $error,
			'last_username' => $lastUsername,
        ]);
    }
}
