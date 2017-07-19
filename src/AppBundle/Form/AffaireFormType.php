<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 *
 * @author NullPointerWizard
 *
 */
class AffaireFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
			->add('nomAffaire')
			->add('year', null, array( 'label'=>'Annee' ))
			->add('numeroAffaire')
			->add('dateButoir', DateType::class, array(
                'label'		 => 'Date butoir',
				'required'	 => false,
				'widget'	 => 'single_text',
				'attr' 		=> ['class' => 'js-datepicker'],
				'html5' 	=> false,
				'format' 	=> 'dd/MM/yyyy'
			))
			->add('travailAEffectuer')
			//->add('idOrganisme', null, array( 'label'=>'Organisme' ))
		;

	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
				'data_class' => 'AppBundle\Entity\Affaires'
		]);
	}
}
