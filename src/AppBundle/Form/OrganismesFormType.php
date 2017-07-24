<?php

namespace AppBundle\Form;

use AppBundle\Entity\Organismes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author NullPointerWizard
 *
 */
class OrganismesFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('nomOrganisme', null, array(
			'label'      => 'Nom de l\'organisme',
            'required'   => true
		))
		;

	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
				'data_class' => Organismes::class
		]);
	}
}
