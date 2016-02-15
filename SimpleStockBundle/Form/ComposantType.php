<?php

namespace SYM16\SimpleStockBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ComposantType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 		'text')
	    ->add('createur',		'text')
	    ->add('creation',		'datetime')
	    ->add('modification', 	'datetime')
            ->add('famille', 'entity', array(
		'class' => 'SYM16SimpleStockBundle:Famille',
		'property' => 'nom'
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SYM16\SimpleStockBundle\Entity\Composant'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sym16_simplestockbundle_composant';
    }
}
