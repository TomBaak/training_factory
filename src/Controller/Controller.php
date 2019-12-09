<?php


    namespace App\Controller;

    use App\Entity\Person;
    use App\Entity\Training;
	use Doctrine\ORM\Mapping\Entity;
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
		 * @Route("/noAccess", name="noAccess")
		 */
		public function noAccess()
		{
			return $this->render('noAccess.html.twig');
		}

    }