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

    public function __construct(Doctrine $doctrine, Request $request)
    {
	$this->request = $request;
	$this->doctrine = $doctrine;
    }

    public function validate($entity, Constraint $constraint)
    {
	//on récupère l'entity manager associé à la connexion (c'est à dire à la bdd)
	$em = $this->doctrine->getManager($constraint->connexion);
	// on récupère le repository de cette entité
	$rep = $em->getRepository(get_class($entity));
	// on construir le getter pour l'attribut de l'entité récupéré en paramètres de la contrainte (le champ "field")
	$getter = "get".ucfirst($constraint->field);
	// on construit le findBy pour faire un findBy de l'attribut
	$repositoryMethod = $constraint->repositoryMethod.ucfirst($constraint->field);
	// on récupère la valeur de l'attribut
	$value = $entity->{$getter}();
	// on teste si cette valeur n'existe pas déjà dans la table
        if($rep->{$repositoryMethod}($value) <> null )
            $this->context->addViolation($constraint->message);
    }
}

