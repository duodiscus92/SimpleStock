<?php

namespace SYM16\SimpleStockBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntrepotModifierType extends EntrepotType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	// on appelle la fonction de la classe mère
	parent::buildForm($builder, $options);
	// on neutralise l'attribut date dont on veut empecher la modif
	//$builder->remove('creation');
	//$builder->remove('modification');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sym16_simplestockbundle_entrepotmodifier';
    }
}
