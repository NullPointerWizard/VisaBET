<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author NullPointerWizard
 *
 */
class RemarqueFormType extends AbstractType{

	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('idTypeRemarque', null, array('label'=>'Type'))
        ->add('remarque')
		;

	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
				'data_class' => 'AppBundle\Entity\RemarquesVisa'
		]);
	}
}
