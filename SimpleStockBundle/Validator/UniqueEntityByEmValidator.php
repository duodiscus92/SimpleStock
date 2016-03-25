<?php
// src/SYM16/SimpleStockBundle/Validator/UniqueEntityByEmValidator.php

namespace SYM16\SimpleStockBundle\Validator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

class UniqueEntityByEmValidator extends ConstraintValidator
{
    private $doctrine;
    private $request;
    private $connexion;
    //private $em;
 
    public function __construct(Request $request, Registry $doctrine)
    {
	$this->request = $request;
	$this->doctrine = $doctrine;
	//$this->em = $em;
	$this->connexion = $request->getSession()->get('_connexion');
    }

    public function validate($entity, Constraint $constraint)
    {
	$em = $this->doctrine->getManager($this->connexion);
	$rep = $em->getRepository(get_class($entity));
	$getter = "get".ucfirst($constraint->field);
	$repositoryMethod =$constraint->repositoryMethod.ucfirst($constraint->field);
	$value = $entity->{$getter}();
        if($rep->{$repositoryMethod}($value) <> null )
            $this->context->addViolation($constraint->message);
    }
}

