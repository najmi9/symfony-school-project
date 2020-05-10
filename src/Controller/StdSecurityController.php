<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StdSecurityController extends AbstractController
{
    /**
     * @Route("/student/login", name="app_user_login")
     */
    public function login()
    {
        return $this->render('std_security/index.html.twig', [
          
        ]);
    }

     /**
     * @Route("/student/logout", name="app_user_logout")
     */
    public function logout()
    {
        
    }
}
