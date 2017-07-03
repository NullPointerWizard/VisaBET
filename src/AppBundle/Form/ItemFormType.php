<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author NullPointerWizard
 *
 */
class ItemFormType extends AbstractType{

	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('type')
		->add('tag')
		->add('nomItem')
		;
		
	}
	
	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
				'data_class' => 'AppBundle\Entity\Items'
		]);
	}
}