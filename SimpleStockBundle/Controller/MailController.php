<?php
// src/SYM16/SimpleStockBundle/Controller/MailController.php
namespace SYM16\SimpleStockBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SYM16\UserBundle\Entity\User;

/**
 *
 * Classe Mail
 *
 * @Route("/mail")
 */
class MailController extends Controller
{
    private $mailsender= 'simplestock@free.fr';
    /**
     * envoyer un mail de confirmation à un nouvel inscrit
     *
     * @Route("/confreg/{id}", name="sym16_simple_stock_mail_confreg")
     */
    public function confregMailAction($id)
    {
	// recupération de l'entity manager
	$em = $this->getDoctrine()->getManager('stockmaster');
        //récupérartion de l'entite d'id  $id
        $entity = $em->getRepository("SYM16UserBundle:User")->find($id);

        $message = \Swift_Message::newInstance()
           ->setSubject("[SimpleStock] Confirmation d'inscription")
           ->setFrom($this->mailsender)
           ->setTo($entity->getEmail())
           ->setBody(
                 $this->renderView(
                    'SYM16SimpleStockBundle:Mails:registration.html.twig',
                     array('nom' => $entity->getNom(), 'prenom' => $entity->getPrenom())
                )
           )
        ;
        $this->get('mailer')->send($message);
        return $this->render('SYM16SimpleStockBundle:Common:inforegistrationdone.html.twig',
            array('statut' => 'TEMPORAIRE', 'homepath' => "sym16_simple_stock_homepage"));
     }
}

