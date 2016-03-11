<?php

namespace SYM16\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 	'text', array('label' => 'Identifiant de connection') )
            //->add('nom',	'text')
            //->add('prenom',	'text')
            ->add('password',	'password', array('label' => 'Mot de passe') )
            /*->add('password',	'password', array(
		'repeatedpassword' => 'repeated',
		'mapped' => 'false',
		'type' => 'password',
		) ) */
	    //->add('statut',	'text')
            ->add('statut', 'entity', array(
		'class' => 'SYM16UserBundle:Role',
		'property' => 'role',
		'em' => 'stockmaster'
            ));
            //->add('mail')
	    //->add('asb', 	'checkbox', array('required' => false))
            //->add('adp')
            //->add('art')
            //->add('acr')
	    ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SYM16\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sym16_Userbundle_user';
    }
}
