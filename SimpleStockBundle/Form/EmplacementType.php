<?php

namespace SYM16\SimpleStockBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EmplacementType extends AbstractType
{
    private $options = array();
    public function __construct($options = array('em' => 'default'))
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
	// construction du formulaire
        $builder
            ->add('nom', 		'text')
	    //->add('createur',		'text')
	    //->add('creation',		'datetime')
	    //->add('modification', 	'datetime')
            ->add('entrepot', 'entity', array(
		'class' => 'SYM16SimpleStockBundle:Entrepot',
		'property' => 'nom',
		'em' => $em
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SYM16\SimpleStockBundle\Entity\Emplacement'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sym16_simplestockbundle_emplacement';
    }
}
