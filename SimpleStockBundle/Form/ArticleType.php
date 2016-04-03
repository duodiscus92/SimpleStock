<?php

namespace SYM16\SimpleStockBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Doctrine\ORM\EntityRepository;
use SYM16\SimpleStockBundle\Form\ReferenceType;

class ArticleType extends AbstractType
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
            // la liste déroulante  reference
	    ->add('reference', 'entity', array(
		'required' => false,
		'class' => 'SYM16SimpleStockBundle:Reference',
		'query_builder' => function(EntityRepository $er){
			return $er->createQueryBuilder('reference')->orderBy('reference.ref','ASC');
		},
		'property' => 'nom',
		'em' => $em,
		'label' => 'Référence',
		'empty_value' => "-- Selectionnez une référence --",
            ))
	    ->add('commentaire',		'text', array('required' => false, 'label' => 'Commentaire'))
            ->add('prixht', 		'number', array('precision' => '2', 'label' => 'Prix HT'))
            ->add('tva', 		'number', array('precision' => '2', 'label' => 'TVA'))
	    ->add('quantite', 		'integer', array('label' => 'Quantité'))
	    //->add('createur',		'text', array('label' => 'Créateur'))
	    //->add('creation',		'datetime', array('label' => 'Création'))
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
            'data_class' => 'SYM16\SimpleStockBundle\Entity\Article'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sym16_simplestockbundle_article';
    }
}
