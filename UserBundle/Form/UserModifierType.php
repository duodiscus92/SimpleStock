<?php

namespace SYM16\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserModifierType extends UserType
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
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sym16_Userbundle_usermodifier';
    }

    /**
     * @param OptionsResolverInterface $resolver
    */
    /*public function setDefaultOptions(OptionsResolverInterface $resolver)  
    {
        $resolver->setDefaults(array(
            'validation_groups' =>  array('default'),
        ));
    }*/

    /**
     * @return array()
    */
    /*public function getDefaultOptions(array $options)
    {
        return array(
            'validation_groups' => array('default'),
        );
    }*/
}
