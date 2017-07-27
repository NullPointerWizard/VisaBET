<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * Classe permettant de sélectionner un document parmi ceux des affaires de l'utilisateur
 * @author NullPointerWizard
 *
 */
class ChoixDocFormType extends AbstractType{

	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('idDocument',null,array(
			'label'=>'Document'
		))
		->add('etatVisa', ChoiceType::class, array(
			'choices'	=> array(
				'F (Favorable)'		 			=> 'OK',
				'C (favorable, sous Condition)'	=> 'OK REM',
				'S (Suspendu)'					=> 'REM',
				'D (Défavorable)' 				=> 'NC',
				'HM (Hors Mission)' 			=> 'NC'
				// 'OK'		 																		=>	'OK',
				// 'OK REM (début des travaux si prise en compte des REMarques, documents à renvoyer)'	=>	'OK REM',
				// 'OK TEC (visa TEChnique, nécessite visa architecte)'								=>	'OK TEC',
				// 'OK ARCHI (visa ARCHItecte, nécessite visa technique)'								=>	'OK ARCHI',
				// 'REM (REMarques, documents à renvoyer)'												=>	'REM',
				// 'NC (Non Conforme au cahier des charges)'											=>	'NC'
			)
		))
        ->add('indicePlan', null, array(
			'label' 	=> 'Indice du plan',
			'required'	=> false
		))
        ->add('remarques',CollectionType::class, array(
			'label' 	=> 'Remarques',
            'entry_type'    => RemarqueFormType::class,
            'allow_add'     => true, //parametre lorsqu'on veut plusieurs formulaire remarques
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
