<?php

namespace SYM16\SimpleStockBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UtilisateurType extends AbstractType
{
    private $options = array();
    public function __construct($options = array('em' => 'stockmaster'))
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
        $builder
            //->add('login')
            ->add('nom',	'text')
            ->add('prenom',	'text')
            //->add('mdp')
            //->add('mail')
	    ->add('asb', 	'checkbox', array('required' => false))
            //->add('adp')
            //->add('art')
            //->add('acr')
            ->add('date',	'datetime')
            ->add('droit', 'entity', array(
		'class' => 'SYM16SimpleStockBundle:Droit',
		'property' => 'privilege',
		'em' => $em
	    ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SYM16\SimpleStockBundle\Entity\Utilisateur'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sym16_simplestockbundle_utilisateur';
    }
}
