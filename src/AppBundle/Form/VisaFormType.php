<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * @author NullPointerWizard
 *
 */
class VisaFormType extends AbstractType{

	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('idDocument',null,array('label'=>'Document'))
		->add('etatVisa')
        ->add('indicePlan')
        ->add('visePar',null,array('label'=>'Examiné par'))

        ->add('remarques',CollectionType::class, array(
            'entry_type'    => RemarqueFormType::class,
            'allow_add'     => true, //paramatre lorsqu'on veut plusieurs formulaire remarques
            'allow_delete'  => true,
            'by_reference'  => false
        ))
		;

	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
				'data_class' => 'AppBundle\Entity\Visas'
		]);
	}
}
