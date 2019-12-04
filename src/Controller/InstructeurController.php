<?php
	
	
	namespace App\Controller;
	
	
	use App\Entity\Lesson;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use App\Form\TrainingType;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	
	class InstructeurController extends AbstractController
	{
		/**
		 * @Route("/instructeur", name="instructeur")
		 */
		public function instructeur()
		{
			return $this->render('instructeur/home.html.twig');
		}
		
		/**
		 * @Route("/instructeur/lessen", name="instructeur_lessen")
		 */
		public function instructeurLessen()
		{
			$lessen = $this->getDoctrine()->getRepository(Lesson::class)->findAll();
			
			return $this->render('instructeur/instructeurLessen.html.twig', [
				
				'lessen' => $lessen,
			
			
			]);
		}
	}