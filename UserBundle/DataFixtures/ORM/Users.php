<?php

// src/SYM16/UserBundle/DataFixtures/ORM/Users.php
namespace SYM16\UserBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SYM16\UserBundle\Entity\User;

class Users implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listNames = array('rehrlich','jehrlich', 'admin', 'root');

    foreach ($listNames as $name) {
      // On crée l'utilisateur
      $user = new User;

      // Le nom d'utilisateur et le mot de passe sont identiques
      $user->setUsername($name);
      //$user->setPassword($name);
      $options = ['cost' => 12];
      $user->setPassword(password_hash($name, PASSWORD_BCRYPT, $options));

      // On ne se sert pas du sel pour l'instant
      $user->setSalt('');
      // On définit uniquement le role ROLE_USER qui est le role de base
      $user->setRoles(array('ROLE_EXAMINATEUR'));
      // On définit  le createur et la date  creation et modification
      $user->setCreateur('root');
      $datetime = new \Datetime();
      $user->setCreation($datetime);
      $user->setModification($datetime);
     

      // On le persiste
      $manager->persist($user);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }
}
