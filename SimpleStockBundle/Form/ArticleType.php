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
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // la liste déroulante  reference
	    ->add('reference', 'entity', array(
		'required' => false,
		'class' => 'SYM16SimpleStockBundle:Reference',
		'query_builder' => function(EntityRepository $er){
			return $er->createQueryBuilder('reference')->orderBy('reference.ref','ASC');
		},
		'property' => 'nom',
		'label' => 'Référence',
		'empty_value' => "-- Selectionnez une référence --",
            ))
            ->add('prixht', 		'number', array('precision' => '2', 'label' => 'Prix HT'))
            ->add('tva', 		'number', array('precision' => '2', 'label' => 'TVA'))
	    ->add('quantite', 		'integer', array('label' => 'Quantité'))
	    ->add('createur',		'text', array('label' => 'Créateur'))
	    ->add('creation',		'datetime', array('label' => 'Création'))
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
