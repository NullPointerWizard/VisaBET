<?php

namespace AppBundle\Form;

use AppBundle\Entity\RoleContact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author NullPointerWizard
 *
 */
class RoleFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('nomRole', null, array(
			'label'      => 'Role',
            'required'   => true
		))
		;

	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
				'data_class' => RoleContact::class
		]);
	}
}
