<?php

namespace SYM16\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 	'text')
            //->add('nom',	'text')
            //->add('prenom',	'text')
            ->add('password',	'text')
	    //->add('statut',	'text')
            ->add('statut', 'entity', array(
		'class' => 'SYM16UserBundle:Role',
		'property' => 'role'
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
