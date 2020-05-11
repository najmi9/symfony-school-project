<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StudentRepository;
use App\Repository\ProfRepository;
use App\Repository\ClasseRepository;
use App\Form\StdProfileType;
use App\Form\ProfType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Prof;
use App\Entity\Student;

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
     * @Route("/admin/student/profile/{id}", name="admin_edit_profile_student")
     * @return [type] [description]
     */
    public function editStudentProfile(Student $std, Request $request, EntityManagerInterface $manager)
    {
     
                $stdProfile = $std->getProfile();
                
              $form = $this->createForm(StdProfileType::class, $stdProfile);
              $form->handleRequest($request);
       
              if ($form->isSubmitted() && $form->isValid()) {
                //dd($request->request->get('std_profile')['state']);
              $stdProfile->setState($request->request->get('std_profile')['state'])
                        ->setNumber($request->request->get('std_profile')['number'])
                         ->setNotes($request->request->get('std_profile')['notes']);
                 
                  $std->setProfile($stdProfile);
                  $manager->persist($std);

                   $manager->flush();
                   
                   $this->addFlash('message', 'Ok, User edited successfly');
                   return $this->redirectToRoute('admin_students');
              }

              return $this->render("admin/edit-student.html.twig",[
        'profileForm'=>$form->createView(),
        'info'=>$std->getStdperinfo()
              ]);
    }



    /**
     * @Route("/admin/student/delete/{id}", name="admin_delete_student")
     */
    public function deleteStudent(Student $std,EntityManagerInterface $manager)
    {               
                  $manager->remove($std);

                   $manager->flush();
                   
                   $this->addFlash('message', 'Ok, User removed successfly');
                   return $this->redirectToRoute('admin_students');
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
        	return $this->redirectToRoute('admin_profs');
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

     /**
      * @Route("/admin/classes", name="admin_classes")
      */
     
     public function classes(ClasseRepository $classeRepo ){
        
       return $this->render('admin/classes.html.twig', [
          'classes'=>$classeRepo->findAll() 
       ]);
     }
     
     /**
      * @Route("/admin/class/edit/{id}", name="admin_edit_class")
      */
     public function editClass()
     {

      return $this->render("admin/edit-class.html.twig",[
          'classForm'=>$form->createView()
      ]);
     }
}