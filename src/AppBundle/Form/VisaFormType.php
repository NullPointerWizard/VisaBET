<?php
namespace AppBundle\Form;

use AppBundle\Repository\DocumentsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
		$lot = $options['data']['lot'];
		$builder
		->add('document', EntityType::class , array(
			'label'     	=> 'Documents',
			'class' 		=> 'AppBundle\Entity\Documents',

			'query_builder' => function(DocumentsRepository $repo) use ($lot){
				return $repo->getDocumentsFromLotsQueryBuilder($lot);
			},
			//Permet de gerer la forme d'affichage(checkbox, tag, radio)
			'multiple' 		=> false, //change la valeur de retour en ArrayCollection
			'expanded' 		=> false,

			'group_by' => function($val, $key, $index) {
				return $val->getType();
			},
		))
		->add('etatVisa', ChoiceType::class, array(
			'label'		=> 'Avis',
			'required'	=> true,
			'choices'	=> array(
				'S (Suspendu)'					=> 'S',
				'F (Favorable)'		 			=> 'F',
				'C (favorable, sous Condition)'	=> 'C',
				'D (Défavorable)' 				=> 'D',
				'HM (Hors Mission)' 			=> 'HM'
				// 'OK'		 																		=>	'OK',
				// 'OK REM (début des travaux si prise en compte des REMarques, documents à renvoyer)'	=>	'OK REM',
				// 'OK TEC (visa TEChnique, nécessite visa architecte)'								=>	'OK TEC',
				// 'OK ARCHI (visa ARCHItecte, nécessite visa technique)'								=>	'OK ARCHI',
				// 'REM (REMarques, documents à renvoyer)'												=>	'REM',
				// 'NC (Non Conforme au cahier des charges)'											=>	'NC'
			)
		))
        // ->add('indicePlan', null, array(
		// 	'label' 	=> 'Indice du plan',
		// 	'required'	=> false
		// ))
        ->add('remarques', CollectionType::class, array(
			'label' 		=> 'Remarques',
			'required'		=> false,
            'entry_type'    => RemarqueFormType::class,
            'allow_add'     => true, //parametre lorsqu'on veut plusieurs formulaire remarques
            'allow_delete'  => true,
            'by_reference'  => false
        ))
		;

	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
				'data_class' => null
		]);
	}
}
