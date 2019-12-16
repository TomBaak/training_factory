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
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\TimeType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class PersonType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			parent::buildForm($builder, $options);
			$builder
				->add('emailaddress', TextType::class, ['label' => 'Email'])
				->add('password', PasswordType::class, ['label' => 'Wachtwoord'])
				->add('firstname', TextType::class, ['label' => 'Voornaam'] )
				->add('preprovision', ChoiceType::class, [
					
					'label' => 'Titel',
					'choices' => [
						
						'Mr.' => 'Mr.',
						'Mevr.' => 'Mvr.'
						
					]
				
				])
				->add('lastname', TextType::class, ['label' => 'Achternaam'])
				->add('dateofbirth', BirthdayType::class, ['label' => 'Geboorte datum'])
				->add('gender', ChoiceType::class, [
					
					'label' => 'Geslacht',
					'choices' => [
						
						'Man' => '1',
						'Vrouw' => '2'
					
					]
				
				])
				->add('street', TextType::class, ['label' => 'Straatnaam'] )
				->add('postalcode', TextType::class, ['label' => 'Postcode'])
				->add('place', TextType::class, ['label' => 'Woonplaats']);
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => Person::class,
			]);
		}
	}