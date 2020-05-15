<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StudentRepository;
use App\Repository\NoteRepository;
use App\Repository\StdChoiceRepository;
use App\Repository\ProfRepository;
use App\Repository\CourseRepository;
use App\Entity\Student;
use App\Entity\User;

class StdStateController extends AbstractController
{
   /**
    * @Route("/student/coures-annonces/{id}", name="student_courses_anounces")
    */
    public function myCoursesAndAnounces(Student $std, CourseRepository $courseRepo, ProfRepository $profRepo){
       
       
      return $this->render('std_state/profile.html.twig',[
     'myclasse'=>$std->getClasse()
      ]);
   }

    /**
     * @Route("/student/profile/{id}", name="student_profile")
     */
    public function index(Student $std)
    {     
        //$std = $stdRepo->findOneByUser($user);
        
        return $this->render('std_state/index.html.twig', [
          'user'=>$std->getUser(),
        'stdInfo'=>$std->getStdperinfo(),
        'stdProfile'=> $std->getProfile(),
        'stdChoice'=>$std->getStdchoice(),
        'stdCv'=>$std->getStdCv() 

        ]);
    }

   /**
    * @Route("/student/notes/{id}", name="student_note")
    */
 public function notes(Student $student, NoteRepository $noteRepo)
 {     
         $notes = [];
         $moyen = 0; 
      if ($student->getProfile()->getState() == "ACCEPTED" ) {
       foreach ($student->getClasse()->getProfs() as $prof) {
         $note = $noteRepo->findOneByProf($prof);
         $notes[] = [
          'note'=>(int)$note->getNote(),
           'matter'=>$note->getProf()->getMatter()
           ];
        }
      
        if (! count($notes) == 0) {
            
       foreach ($notes as $note) {
         $moyen = $moyen +$note['note'];
       }
       $moyen = $moyen/count($notes);
        } 
       
      }
         
         
    
       return $this->render("std_state/notes.html.twig",[
        'notes'=>$notes,
        'total'=>$moyen
        ]);
 }

}
