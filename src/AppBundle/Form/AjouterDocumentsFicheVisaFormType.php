<?php

namespace AppBundle\Form;

use AppBundle\Repository\DocumentsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
/**
 *
 * @author NullPointerWizard
 *
 */
class AjouterDocumentsFicheVisaFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options){
        $lot = $options['data']['lot'];
		$builder
            ->add('fiche',EntityType::class,array(
                'class'         => 'AppBundle\Entity\FicheVisa'
            ))
			->add('documents', EntityType::class , array(
				'label'     	=> 'Documents(s), sélectionner plusieurs avec Ctrl',
				'class' 		=> 'AppBundle\Entity\Documents',

                'query_builder' => function(DocumentsRepository $repo) use ($lot){
    				return $repo->getDocumentsFromLotsQueryBuilder($lot);
				},
				//Permet de gerer la forme d'affichage(checkbox, tag, radio)
				'multiple' 		=> true, //change la valeur de retour en ArrayCollection
     			'expanded' 		=> false,

                'group_by' => function($val, $key, $index) {
                    return $val->getType();
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
