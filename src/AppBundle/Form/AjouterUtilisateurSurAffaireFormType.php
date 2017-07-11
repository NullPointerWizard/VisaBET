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
class AjouterUtilisateurSurAffaireFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
			->add('utilisateurs', EntityType::class , array(
				'label'     	=> 'Utilisateur',
				'class' 		=> 'AppBundle\Entity\Utilisateur',
				//'choices' =>

				//Permet de gerer la forme d'affichage(checkbox, tag, radio)
				'multiple' 		=> true, //change la valeur de retour en ArrayCollection
     			'expanded' 		=> true
			))
		;
	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
				'data_class' => null, //ArrayCollection::class ,
				'validation_groups' => array('Default')
		]);
	}

}
