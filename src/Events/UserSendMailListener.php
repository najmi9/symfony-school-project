<?php
namespace App\Events;

use App\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;



class UserSendMailListener extends AbstractController
{
    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
     

     private $tokenGenerator, $mailer;

    function __construct(TokenGeneratorInterface $tokenGenerator, \Swift_Mailer $mailer)
    {
    	$this->tokenGenerator = $tokenGenerator;
    	$this->mailer = $mailer;
    }

    public function postUpdate(User $user, LifecycleEventArgs $event)
    {       
           $token =$user->getActivationToken();
           $message =( new \Swift_Message("Confirmation de l'émail"))
                    ->setFrom('noreplynajmi@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                            $this->renderView(                   
                            'std_state/email.html.twig',
                            [
                              'user' => $user,
                              'token'=>$token
                            ]
                            )
                     , 'text/html');
            $this->mailer->send($message);
    }
}
