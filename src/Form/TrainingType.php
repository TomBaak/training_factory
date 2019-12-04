<?php
	
	
	namespace App\Form;
	
	
	use App\Entity\Training;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\MoneyType;
	use Symfony\Component\Form\Extension\Core\Type\NumberType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class TrainingType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			parent::buildForm($builder, $options);
			$builder
				->add('name', TextType::class, ['label' => 'Naam:'])
				->add('description', TextAreaType::class, ['label' => 'Beschrijving:'])
				->add('duration', NumberType::class, ['label' => 'Duur:'])
				->add('costs', MoneyType::class, ['label' => 'Kosten:']);
		}

		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => Training::class,
			]);
		}
	}