<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Prof;
use App\Repository\ProfRepository;

class ProfController extends AbstractController
{
    /**
     * @Route("/prof", name="prof")
     */
    public function index()
    {
        return $this->render('prof/index.html.twig', [
            
        ]);
    }

    /**
     * @Route("/prof/profile/{id}", name="prof_profile")
     */
    public function profile(Prof $prof, ProfRepository $profRepo )
    {
        
        $classes = []; 
        foreach ($prof->getClasses() as $classe) {
        	$classes[] =[ 
        		'name'=>$classe->getName(),  
               'stds_nub'=> count($classe->getStudents())
        	];
        }
     

    	return $this->render("prof/profile.html.twig",[
          'classes'=>$classes
    	]);
    }

    /**
     * @Route("/prof/add", name="prof_add_course")
     */
    
    public function add()
    {

    	return $this->render("prof/profile.html.twig",[

    	]);
    }
}
