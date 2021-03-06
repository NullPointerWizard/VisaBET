<?php

namespace AppBundle\Form;

use AppBundle\Entity\Documents;
use AppBundle\Repository\LotsRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
/**
 *
 * @author NullPointerWizard
 *
 */
class UploadFileFormType extends AbstractType {

	/*
	* Attention ce formulaire a besoin de l'affaire
	*/
	public function buildForm(FormBuilderInterface $builder, array $options){
		$document = $options['data'];
		$affaire = $document->getIdAffaire() ;
		$builder
			->add('file', FileType::class, array(
                'label' => 'Document',
				'required'	 => false
            ))
			->add('type', ChoiceType::class, array(
    			'choices'	=> array(
        			'Autre'		 => 'Autre',
			        'Materiel'	 => 'Materiel',
			        'Plan'		 => 'Plan',
					'NDC'		 => 'NDC'
    			)
			))
			->add('filename', null, array(
                'label'		 => 'Nom (* si papier)',
				'required'	 => false
            ))
			->add('lot', null ,array(
				'label'		=> 'Lot',
				'required'	=> true,
				'query_builder' => function(LotsRepository $repo) use ($affaire){
    				return $repo->getLotsFromAffaireQueryBuilder($affaire);
				}
			))
			->add('dateDocument', DateType::class, array(
                'label'		 => 'Date d\'emission',
				'required'	 => false,
				'widget'	 => 'single_text',
				'attr' 		=> ['class' => 'js-datepicker'],
				'html5' 	=> false,
				'format' 	=> 'dd/MM/yyyy'
			))
			->add('dateReception', DateType::class, array(
                'label'		=> 'Date de reception',
				'required'	=> true,
				'widget'	=> 'single_text',
				'attr' 		=> ['class' => 'js-datepicker'],
				'html5' 	=> false,
				'format' 	=> 'dd/MM/yyyy'
			))
			->add('dateLimiteVisa', DateType::class, array(
                'label'		 => 'Date limite',
				'required'	 => true,
				'widget'	 => 'single_text',
				'attr' 		=> ['class' => 'js-datepicker'],
				'html5' 	=> false,
				'format' 	=> 'dd/MM/yyyy'
			))
			->add('indicePlan', null, array(
                'label'		 => 'Indice (si plan)',
				'required'	 => false
            ))
		;
	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
				'data_class' => Documents::class,
		]);
	}

}
