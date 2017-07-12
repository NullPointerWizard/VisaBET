<?php

namespace AppBundle\Form;

use AppBundle\Entity\Lots;
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
		->add('numeroLot', null, array(
			'label' => 'Numero'
		))
		->add('idNomLot', null, array(
			'label' => 'Nom'
		))
		;

	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
				'data_class' => Lots::class
		]);
	}
}
