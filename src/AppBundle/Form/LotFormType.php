<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author NullPointerWizard
 *        
 */
class LotFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('numeroLot')
		->add('idNomLot')
		;
		
	}
	
	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
				'data_class' => 'AppBundle\Entity\Lots'
		]);
	}
}