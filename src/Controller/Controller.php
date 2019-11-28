<?php
	
	
	namespace App\Controller;
	
	use App\Entity\Training;
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

        /**
         * @Route("/trainings_aanbod", name="trainingen")
         */
        public function trainingen(){

            $trainingen = $this->getDoctrine()->getRepository(Training::class)->findAll();


            return $this->render('bezoeker/bezoeker_trainings_aanbod.html.twig', [

               'trainings' => $trainingen,


                ]);



        }

	}