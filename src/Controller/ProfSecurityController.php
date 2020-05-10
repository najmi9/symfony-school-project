<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfSecurityController extends AbstractController
{
    /**
     * @Route("/prof/login", name="app_prof_login")
     */
    public function index()
    {
        return $this->render('prof_security/index.html.twig', [
           
        ]);
    }

   
   /**
    * @Route("/prof/logout", name="app_prof_logout")
    */
    public function logout()
    {
    	
    }
}
