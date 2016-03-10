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
    private $options = array();
    public function __construct($options = array('em' => 'default'))
    {
        $this->options = $options;
    }    

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	// récupération de l'entity manager courant
	$em = $this->options['em'];
	// construction du formulaire
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
		'em' => $em,
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
		'em' => $em,
		'label' => 'Emplacement',
		'empty_value' => "-- Selectionnez un emplacement --",
            ))
            // la liste déroulante famille
	    ->add('famille', 'entity', array(
		'required' => false,
		'class' => 'SYM16SimpleStockBundle:Famille',
		'query_builder' => function(EntityRepository $er){
			return $er->createQueryBuilder('famille')->orderBy('famille.nom','ASC');
		},
		'property' => 'nom',
		'em' => $em,
		'label' => 'Famille',
		'empty_value' => "-- Selectionnez une famille --",
            ))
	    // la liste déroulante composant
            ->add('composant', 'entity', array(
		'required' => false,
		'class' => 'SYM16SimpleStockBundle:Composant',
		'query_builder' => function(EntityRepository $er){
			return $er->createQueryBuilder('composant')->orderBy('composant.nom','ASC');
		},
		'property' => 'nom',
		'em' => $em,
		'label' => 'Composant',
		'empty_value' => "-- Selectionnez un composant --",
            ))
	    ->add('udv', 		'integer')
            ->add('seuil', 		'integer')
	    //->add('createur',		'text') 
	    //->add('creation',		'datetime')
	    //->add('modification', 	'datetime')
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
