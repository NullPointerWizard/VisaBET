<?php

namespace AppBundle\Form;

use AppBundle\Entity\Affaires;
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
			->add('listeUtilisateur', null , array(
                'label'     => 'Utilisateur'
            ))
        ;
	}

    //Cette methode ne fait rien pour ce formulaire
	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
				'data_class' => Affaires::class
		]);
	}
}
