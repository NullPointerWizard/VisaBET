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
class AffaireFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
			->add('nomAffaire')
			->add('year')
			->add('numeroAffaire')
			->add('dateButoir')
			->add('travailAEffectuer')
			->add('idOrganisme')
		;
		
	}
	
	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
				'data_class' => 'AppBundle\Entity\Affaires'
		]);
	}
}