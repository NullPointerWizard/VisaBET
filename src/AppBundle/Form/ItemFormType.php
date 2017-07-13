<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author NullPointerWizard
 *
 */
class ItemFormType extends AbstractType{

	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('type', ChoiceType::class, array(
			'choices'	=> array(
				'Autre'		 => 'Autre',
				'Materiel'	 => 'Materiel',
				'Plan'		 => 'Plan',
				'NDC'		 => 'NDC'
			)
		))
		->add('tag', null , array(
			'required' => false
		))
		->add('nomItem')
		;

	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
				'data_class' => 'AppBundle\Entity\Items'
		]);
	}
}
