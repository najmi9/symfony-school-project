<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StudentRepository;
use App\Repository\StdChoiceRepository;

class StdStateController extends AbstractController
{


    /**
     * @Route("/student/settings", name="setings")
     */    
    public function settings()
    {
        
         return $this->render('std_state/profile.html.twig');
    }
  


    /**
     * @Route("/student/profile/{id}", name="profile")
     */
    public function index($id, StudentRepository $stdRepo)
    {
         $std = $stdRepo->findOneById($id);
         
        return $this->render('std_state/index.html.twig', [
        'stdInfo'=>$std->getStdperinfo(),
        'stdProfile'=> $std->getProfile(),
        'stdChoice'=>$std->getStdchoice(),
        'stdCv'=>$std->getStdCv() 

        ]);
    }




}
