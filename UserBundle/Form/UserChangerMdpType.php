<?php

namespace SYM16\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserChangerMdpType extends UserType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	// on appelle la fonction de la classe mère
	parent::buildForm($builder, $options);
	// on neutralise l'attribut statut dont on veut empecher la modif de la valeur par défaut
	$builder
	->remove('password')
        ->add('oldpassword',	'password', array('label' => 'Ancien mot de passe') )
	->add('password',   'repeated', array(
		'first_name' => 'Nouveau_mot_de_passe',
		'second_name' =>'Confirmation_mot_de_passe',
		'type'	     => 'password') )
	->remove('statut')
	->remove('nom')
	->remove('prenom')
	->remove('statut')
	->remove('email')
	->remove('asb')
	->remove('acp')
	->remove('adr')
	;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sym16_Userbundle_userchangermdp';
    }
}
