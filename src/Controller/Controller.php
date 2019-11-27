<?php
	
	
	namespace App\Controller;
	
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	
	class Controller extends AbstractController
	{
		/**
		 * @Route("/", name="home")
		 */
		public function home(){
			return $this->render('bezoeker/bezoekerhome.html.twig');
		}
		
	}