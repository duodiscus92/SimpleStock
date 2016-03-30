<?php

namespace SYM16\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserModifierMoiType extends UserType
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
	$builder->remove('username');
	$builder->remove('password');
	$builder->remove('statut');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sym16_Userbundle_usermodifiermoi';
    }
}
