<?php

namespace AppBundle\Form;

use AppBundle\Entity\Documents;
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

	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
			->add('file', FileType::class, array(
                'label' => 'Document'
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
                'label'		 => 'Nouveau nom',
				'required'	 => false
            ))
			->add('dateReception', DateType::class, array(
                'label'		=> 'Date de reception',
				'required'	=> false,
				'widget'	=> 'single_text',
				'attr' 		=> ['class' => 'js-datepicker'],
				'html5' 	=> false,
				'format' 	=> 'dd/MM/yyyy'
			))
			->add('dateLimiteVisa', DateType::class, array(
                'label'		 => 'Date limite',
				'required'	 => false,
				'widget'	 => 'single_text',
				'attr' 		=> ['class' => 'js-datepicker'],
				'html5' 	=> false,
				'format' 	=> 'dd/MM/yyyy'
			))
		;
	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
				'data_class' => Documents::class,
		]);
	}

}
