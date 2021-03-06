<?php

namespace SYM16\SimpleStockBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReferenceFiltreType extends AbstractType
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
	    // Radio boutons
            ->add('nomfiltre', 	'choice', array(
	    	'choices' => 		array('t'=> 'Tous', 'u' => 'Contenant la chaîne', 's' => 'Ne contenant pas la chaîne'),
	    	'expanded' => 		true, // radio boutons ou case à cocher
		'multiple' =>		false,	// radio boutons
		'label' =>		'Filtrer selon une chaine de caractères (même partielle)',
		))
	   ->add('nom', 	'text', array('required' => false, 'label' => 'Chaîne à  rechercher') )
	    // Radio boutons 
            ->add('entrepotfiltre', 	'choice', array(
	    	'choices' => 		array('t'=> 'Tous les entrepôts', 'u' => 'Uniquement l\'entrepôt selectionné', 's' => 'Sauf l\'entrepot selectionné'),
	    	'expanded' => 		true, // radio boutons ou case à cocher
		'multiple' =>		false,	// radio boutons
		'label' =>		'Filtrer selon l\'entrepôt selectioné ci-dessous'
		))
            // la liste déroulante entrepot
            ->add('entrepot', 'entity', array(
		'required' => false,
		'class' => 'SYM16SimpleStockBundle:Entrepot',
		'property' => 'nom',
		'em' => $em,
		'label' => 'Entrepôt',
		'empty_value' => "-- Selectionnez un entrepot --",
            ))
	    // Radio boutons 
            ->add('famillefiltre', 	'choice', array(
	    	'choices' => 		array('t'=> 'Toutes les familles', 'u' => 'Uniquement la famille selectionnée', 's' => 'Sauf la famille selectionnée'),
	    	'expanded' => 		true, // radio boutons ou case à cocher
		'multiple' =>		false,	// radio boutons
		'label' =>		'Filtrer selon la famille selectionée ci-dessous'
		))
            // la liste déroulante famille
            ->add('famille', 'entity', array(
		'required' => false,
		'class' => 'SYM16SimpleStockBundle:Famille',
		'property' => 'nom',
		'em' => $em,
		'label' => 'Famille',
		'empty_value' => "-- Selectionnez une famille --",
            ))
	    // provisoirement en commentaire car les categories n'existent pas encore
            //->add('categorie')
            //->add('categoriefiltre')
	    // Radio boutons 
            ->add('createurfiltre', 	'choice', array(
	    	'choices' => 		array('t'=> 'Tous les créateurs', 'u' => 'Uniquement le créateur sélectionné', 's' => 'Sauf le créateur selectionné'),
	    	'expanded' => 		true, // radio boutons ou case à cocher
		'multiple' =>		false,	// radio boutons
		'label' =>		'Filtrer selon le créateur sélectionné ci-dessous'
		))
            // la liste déroulante  createur
	    ->add('createur', 'entity', array(
		'required' => false,
		'class' => 'SYM16UserBundle:User',
		'property' => 'username',
		'label' => 'Créateur',
		'empty_value' => "-- Selectionnez un créateur --",
		'em' => 'stockmaster',
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SYM16\SimpleStockBundle\Entity\ReferenceFiltre'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sym16_simplestockbundle_referencefiltre';
    }
}
