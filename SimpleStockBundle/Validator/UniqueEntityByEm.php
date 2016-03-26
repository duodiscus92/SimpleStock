<?php
// src/SYM16/SimpleStockBundle/Validator/UniqueEntityByEm.php
namespace SYM16\SimpleStockBundle\Validator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueEntityByEm extends Constraint
{
    // 
    public $field = array();
    public $repositoryMethod = 'findBy';
    public $message = "Nom déjà utilisé";
    public $connexion = 'default';

    //
    public function validatedBy()
    {
	return 'isunique';
    }

    //
    public function getRequiredOptions()
    {
	return array('field');
    }

    //
    public function getTargets()
    {
	return self::CLASS_CONSTRAINT;
    }

    public function getDefaultOption()
    {
        return 'field';
    }
}

