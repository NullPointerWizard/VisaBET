<?php

namespace AppBundle\Form;

use AppBundle\Entity\NomsLots;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author NullPointerWizard
 *
 */
class NomLotFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('nomLot', null, array(
			'label'      => 'Nom de lot',
            'required'   => true
		))
		;

	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
				'data_class' => NomsLots::class
		]);
	}
}
