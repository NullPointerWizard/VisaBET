<?php
namespace AppBundle\Form;

use AppBundle\Repository\ItemsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author NullPointerWizard
 *
 */
class EmissionAvisFormType extends AbstractType{

	public function buildForm(FormBuilderInterface $builder, array $options){
		$lot = $options['data']['lot'];
		$builder
		->add('item', EntityType::class , array(
			'label'     	=> 'Sélectionner un item du lot',
			'class' 		=> 'AppBundle\Entity\Items',

			'query_builder' => function(ItemsRepository $repo) use ($lot){
				return $repo->getItemsFromLotsQueryBuilder($lot);
			},
			//Permet de gerer la forme d'affichage(checkbox, tag, radio)
			'multiple' 		=> false, //change la valeur de retour en ArrayCollection
			'expanded' 		=> false,

			'group_by' => function($val, $key, $index) {
				return $val->getType().' '.$val->getTag();
			},

		))
		->add('visa', VisaFormType::class, array(
			'label'     	=> 'Avis pour cet item',
			'data'			=> array('lot'=> $lot)
		))
		;

	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
				'data_class' => null
		]);
	}
}
