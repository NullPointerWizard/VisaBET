<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author NullPointerWizard
 *
 */
class ContactFormType extends AbstractType{

	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
        ->add('organisme', null , array(
			'required' => false
		))
        ->add('nom', null , array(
		))
        ->add('prenom', null , array(
		))
        ->add('role', null , array(
		))
        ->add('mail', null , array(
            'required' => false
		))
        ->add('tel', null , array(
            'required' => false
		))

		;

	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
				'data_class' => 'AppBundle\Entity\Contact'
		]);
	}
}
