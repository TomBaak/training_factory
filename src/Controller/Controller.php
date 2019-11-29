<?php


    namespace App\Controller;

    use App\Entity\Person;
    use App\Entity\Training;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class Controller extends AbstractController
    {
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
		 * @Route("/trainer", name="trainer")
		 */
		public function trainer()
		{
			return $this->render('trainer/home.html.twig');
		}
	
		/**
		 * @Route("/trainer/trainingen", name="trainer_trainingen")
		 */
		public function trainerTrainingen()
		{
			$trainingen = $this->getDoctrine()->getRepository(Training::class)->findAll();
			
			
			return $this->render('trainer/trainingsAanbod.html.twig', [
				
				'trainings' => $trainingen,
			
			
			]);
		}
	
		/**
		 * @Route("/trainer/newTraining", name="trainer_newTraining")
		 */
		public function newTraining()
		{
			return $this->render('trainer\nieuweTraining.hmtl.twig');
		}
    }