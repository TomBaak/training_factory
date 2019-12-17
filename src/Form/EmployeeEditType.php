<?php
	
	
	namespace App\Form;
	
	use App\Entity\Person;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\DateType;
	use Symfony\Component\Form\Extension\Core\Type\PasswordType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class EmployeeEditType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			parent::buildForm($builder, $options);
			$builder
				->add('emailaddress', TextType::class, ['label' => 'Email'])
				->add('firstname', TextType::class, ['label' => 'Voornaam'] )
				->add('lastname', TextType::class, ['label' => 'Achternaam'])
				->add('dateofbirth', BirthdayType::class, ['label' => 'Geboorte datum']);
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => Person::class,
			]);
		}
	}