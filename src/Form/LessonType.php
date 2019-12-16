<?php
	
	
	namespace App\Form;
	
	
	use App\Entity\Lesson;
	use App\Entity\Location;
	use App\Entity\Person;
	use App\Entity\Training;
	use Doctrine\ORM\EntityRepository;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\DateType;
	use Symfony\Component\Form\Extension\Core\Type\NumberType;
	use Symfony\Component\Form\Extension\Core\Type\TimeType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class LessonType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			parent::buildForm($builder, $options);
			$builder
				->add('time', TimeType::class, ['label' => 'Tijd:'])
				->add('date', DateType::class, [
					
					'label' => 'Datum:',
				
				
				])
				->add('max_persons', NumberType::class, [
					
					'label' => 'Maximaal aantal deelnemers:'
				
				])
				->add('training', EntityType::class, [
					
					'placeholder' => 'Kies een training',
					'class' => Training::class,
					'choice_label' => 'name',
					'label' => 'Training:'
				
				
				])
				->add('location_id', EntityType::class, [
					
					'placeholder' => 'Kies een locatie',
					'class' => Location::class,
					'choice_label' => 'street',
					'label' => 'Locatie:'
				
				
				]);
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => Lesson::class,
			]);
		}
	}