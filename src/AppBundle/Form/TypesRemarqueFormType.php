<?php

namespace AppBundle\Form;

use AppBundle\Entity\TypesRemarque;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author NullPointerWizard
 *
 */
class TypesRemarqueFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('typeRemarque', null, array(
			'label'      => 'Type de remarque',
            'required'   => true
		))
		;

	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
				'data_class' => TypesRemarque::class
		]);
	}
}
