<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
		->add('etatVisa', ChoiceType::class, array(
			'choices'	=> array(
				'OK'		 																		=>	'OK',
				'OK REM (début des travaux si prise en compte des REMarques, documents à renvoyer)'	=>	'OK REM',
				'OK TEC (visa TEChnique, nécessite visa architecte)'								=>	'OK TEC',
				//'OK ARCHI (visa ARCHItecte, nécessite visa technique)'								=>	'OK ARCHI',
				'REM (REMarques, documents à renvoyer)'												=>	'REM',
				'NC (Non Conforme au cahier des charges)'											=>	'NC'
			)
		))
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
