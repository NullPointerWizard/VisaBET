<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
/**
 *
 * @author NullPointerWizard
 *
 */
class AjouterContactListeDiffusionFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
			->add('contacts', EntityType::class , array(
				'label'     	=> 'Contact(s), sélectionner plusieurs avec Ctrl',
				'class' 		=> 'AppBundle\Entity\Contact',
				//Permet de gerer la forme d'affichage(checkbox, tag, radio)
				'multiple' 		=> true, //change la valeur de retour en ArrayCollection
     			'expanded' 		=> false,

                'group_by' => function($val, $key, $index) {
                    return $val->getRole();
                },
			))
		;
	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
				'data_class' => null,
		]);
	}

}
