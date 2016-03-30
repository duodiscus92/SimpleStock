<?php

namespace SYM16\SimpleStockBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticlePreleverType extends ArticleType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	// on appelle la fonction de la classe mère
	parent::buildForm($builder, $options);
	// on neutralise les attribut date dont on a pas besoin
	$builder->remove('prixht');
	$builder->remove('tva');
	$builder->remove('quantite');
	$builder->add('aprelever', 	'integer', array('label' => 'Quantité à prélever'));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sym16_simplestockbundle_articleprelever';
    }
}
