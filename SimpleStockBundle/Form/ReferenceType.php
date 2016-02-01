<?php

namespace SYM16\SimpleStockBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Doctrine\ORM\EntityRepository;
use SYM16\SimpleStockBundle\Form\EmplacementType;

class ReferenceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ref', 		'text')
            ->add('nom', 		'text')
            // la liste déroulante entrepot
	    ->add('entrepot', 'entity', array(
		'required' => false,
		'class' => 'SYM16SimpleStockBundle:Entrepot',
		'query_builder' => function(EntityRepository $er){
			return $er->createQueryBuilder('entrepot')->orderBy('entrepot.nom','ASC');
		},
		'property' => 'nom',
		'label' => 'Entrepot',
		'empty_value' => "-- Selectionnez un entrepot --",
            ))
	    // la liste déroulante emplacement
            ->add('emplacement', 'entity', array(
		'required' => false,
		'class' => 'SYM16SimpleStockBundle:Emplacement',
		'query_builder' => function(EntityRepository $er){
			return $er->createQueryBuilder('emplacement')->orderBy('emplacement.nom','ASC');
		},
		'property' => 'nom',
		'label' => 'Emplacement',
		'empty_value' => "-- Selectionnez un emplacement --",
            ))
	    ->add('udv', 		'integer')
            ->add('seuil', 		'integer')
	    ->add('createur',		'text') 
	    ->add('creation',		'datetime')
	    ->add('modification', 	'datetime')
	    // si on met le bouton ici il ne prend pas le style bootstrap
            //->add('valider', 'submit', array(
            //    'label' => 'Valider'
            //    ))
	    ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SYM16\SimpleStockBundle\Entity\Reference'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sym16_simplestockbundle_reference';
    }
}
