<?php

namespace AppBundle\Form;

use AppBundle\Entity\Documents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            // ->add('save', SubmitType::class, array(
            //     'attr' => array('class' => 'save'),
            //     'label' => 'Ajouter les documents'
            // ))
		;
	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
				'data_class' => Documents::class,
		]);
	}

}
