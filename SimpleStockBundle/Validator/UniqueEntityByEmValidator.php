<?php
// src/SYM16/SimpleStockBundle/Validator/UniqueEntityByEmValidator.php

namespace SYM16\SimpleStockBundle\Validator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Component\HttpFoundation\Session\Session;
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
	// recupération de la session
	$session = $this->request->getSession();
	// récupération de la vriable de session contenant le nom interne de la connection à la BDD courante
	$stockconnection = $session->get('stockconnection');
	//on récupère l'entity manager associé à la connexion (c'est à dire à la bdd)
	//$em = $this->doctrine->getManager($constraint->connexion);
	$em = $this->doctrine->getManager($stockconnection);
	// on récupère le repository de cette entité
	$rep = $em->getRepository(get_class($entity));
	// on construit le getter pour l'attribut de l'entité récupéré en paramètres de la contrainte (le champ "field")
	$getter = "get".ucfirst($constraint->field);
	// on construit le findBy pour faire un findBy de l'attribut
	$repositoryMethod = $constraint->repositoryMethod.ucfirst($constraint->field);
	// on récupère la valeur de l'attribut
	$value = $entity->{$getter}();
	// on teste si cette valeur n'existe pas déjà dans la table
        if( ($notunique=$rep->{$repositoryMethod}($value)) <> null ){
	    // et si c'est pas elle-même
	    if($notunique->getId() <> $entity->getId())
                $this->context->addViolation($constraint->message);
        }
    }
}

