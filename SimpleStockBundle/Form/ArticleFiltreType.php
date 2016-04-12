<?php

namespace SYM16\SimpleStockBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleFiltreType extends AbstractType
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
            ->add('recherchefiltre', 	'choice', array(
	    	'choices' => 		array('t'=> 'Tous', 'u' => 'Contenant la chaîne', 's' => 'Ne contenant pas la chaîne'),
	    	'expanded' => 		true, // radio boutons ou case à cocher
		'multiple' =>		false,	// radio boutons
		'label' =>		'Filtrer selon une chaine de caractères (même partielle)',
		))
	   ->add('recherche', 	'text', array('required' => false, 'label' => 'Chaîne à  rechercher') )
	    // Radio boutons 
            ->add('referencefiltre', 	'choice', array(
	    	'choices' => 		array('t'=> 'Toutes les références', 'u' => 'Uniquement la référence selectionnée', 's' => 'Sauf la référence selectionnée'),
	    	'expanded' => 		true, // radio boutons ou case à cocher
		'multiple' =>		false,	// radio boutons
		'label' =>		'Filtrer selon la référence selectionée ci-dessous'
		))
            // la liste déroulante entrepot
            ->add('reference', 'entity', array(
		'required' => false,
		'class' => 'SYM16SimpleStockBundle:Reference',
		'property' => 'ref',
		'em' => $em,
		'label' => 'Réference',
		'empty_value' => "-- Selectionnez une Référence --",
            ))
	    // Radio boutons 
            ->add('nomfiltre', 	'choice', array(
	    	'choices' => 		array('t'=> 'Tous les noms', 'u' => 'Uniquement le nom selectionné', 's' => 'Sauf le nom selectionné'),
	    	'expanded' => 		true, // radio boutons ou case à cocher
		'multiple' =>		false,	// radio boutons
		'label' =>		'Filtrer selon le nom selectioné ci-dessous'
		))
            // la liste déroulante famille
            ->add('nom', 'entity', array(
		'required' => false,
		'class' => 'SYM16SimpleStockBundle:Reference',
		'property' => 'nom',
		'em' => $em,
		'label' => 'Nom',
		'empty_value' => "-- Selectionnez un nom --",
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
            'data_class' => 'SYM16\SimpleStockBundle\Entity\ArticleFiltre'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sym16_simplestockbundle_articlefiltre';
    }
}
