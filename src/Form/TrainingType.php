<?php
	
	
	namespace App\Forms;
	
	
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\NumberType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class TrainingType extends AbstractType
	{
		
		public function buildForm(FormBuilderInterface $builder, array $options){
			parent::buildForm($builder, $options);
			$builder
				->add('name', TextType::class)
				->add('description', TextAreaType::class)
				->add('duration', NumberType::class)
				->add('costs', NumberType::class)
			;
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => 'App\Entity\Training'
			]);
		}
	}