<?php
	
	
	namespace App\Form;
	
	use App\Entity\Person;
	use Doctrine\ORM\EntityRepository;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\DateType;
	use Symfony\Component\Form\Extension\Core\Type\NumberType;
	use Symfony\Component\Form\Extension\Core\Type\PasswordType;
	use Symfony\Component\Form\Extension\Core\Type\TimeType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class PersonType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			parent::buildForm($builder, $options);
			$builder
				->add('emailaddress')
				->add('password', PasswordType::class)
				->add('firstname')
				->add('preprovision', ChoiceType::class, [
				
					'choices' => [
						
						'Mr.' => 'Mr.',
						'Mvr.' => 'Mvr.'
						
					]
				
				])
				->add('lastname')
				->add('dateofbirth', BirthdayType::class)
				->add('gender', ChoiceType::class, [
					
					'choices' => [
						
						'Man' => '1',
						'Vrouw' => '2'
					
					]
				
				])
				->add('street')
				->add('postalcode')
				->add('place');
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => Person::class,
			]);
		}
	}