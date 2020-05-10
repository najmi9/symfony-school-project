<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StudentRepository;
use App\Repository\ProfRepository;
use App\Repository\ClasseRepository;
use App\Form\ProfType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Prof;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
  /**
   * @Route("/admin", name="admin_home")
   */
  public function home(){
          
      return $this->render("admin/home.html.twig");
  }

    /**
     * @Route("/admin/students", name="admin_students")
     */
    public function index(StudentRepository $stdRepo, ProfRepository $profRepo)
    {
    	
    	
        return $this->render('admin/students.html.twig', [
           'students'=>$stdRepo->findAll(),
           'profs'=>$profRepo->findAll()
        ]);
    }
  
   /**
    * @Route("/admin/prof/new", name="admin_new_prof")
    */
    public function new(Request $request, EntityManagerInterface $manager, ClasseRepository $classes, UserPasswordEncoderInterface $passwordEncoder) 
    {
        $prof = new Prof();
        $classe = $classes->findAll();

        $form=$this->createForm(ProfType::class, $prof);
        $form->handleRequest($request);    
        if ($form->isSubmitted() && $form->isValid()) {  
          
         $prof->setPassword($passwordEncoder->encodePassword($prof, $form->get('password')->getData()));

        	$manager->persist($prof);
        	$manager->flush();
        	$this->addFlash('message', 'the prof has been added successfly !');
        	return $this->redirectToRoute('admin');
        }
        return $this->render('admin/prof.html.twig',[
       'profForm'=>$form->createView()
      ]);
     }



     /**
      * @Route("/admin/profs", name="admin_profs")
      */
     
     public function profs(ProfRepository $profRepo){
        
       return $this->render('admin/profs.html.twig', [
           'profs'=>$profRepo->findAll()
       ]);
     }
}