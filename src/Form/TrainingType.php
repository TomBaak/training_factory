<?php
	
	
	namespace App\Form;
	
	
	use App\Entity\Training;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\FileType;
	use Symfony\Component\Form\Extension\Core\Type\MoneyType;
	use Symfony\Component\Form\Extension\Core\Type\NumberType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Validator\Constraints\File;
	
	class TrainingType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			parent::buildForm($builder, $options);
			$builder
				->add('name', TextType::class, ['label' => 'Naam:'])
				->add('description', TextAreaType::class, ['label' => 'Beschrijving:'])
				->add('duration', NumberType::class, ['label' => 'Duur:'])
				->add('costs', MoneyType::class, ['label' => 'Kosten:'])
				->add('image_filename', FileType::class, [
					
					'label' => 'Trainings afbeeling:',
					'mapped' => false,
					'required' => false,
					
					//TODO: Making sure you can only upload images
					
					'constraints' => [
						new File([
							'maxSize' => '10240k'
						])
					]
					
				]);
		}

		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => Training::class,
			]);
		}
	}