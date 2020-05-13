<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnounceRepository;
use App\Repository\StudentRepository;
use App\Repository\ProfRepository;
use App\Entity\Prof;
use App\Entity\Note;
use App\Entity\Anounce;
use App\Form\AnounceType;
use App\Form\NoteType;
use App\Entity\Course;
use App\Form\CourseType;
use Doctrine\ORM\EntityManagerInterface;

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
    public function profile(Prof $prof)
    {
    	return $this->render("prof/profile.html.twig",[
          'prof'=>$prof
    	]);
    }

    /**
     * @Route("/prof/course/add", name="prof_add_course")
     * @Route("/prof/cour/edit/{id}", name="prof_edit_course")
     */
    
    public function course(Course $course=null, Request $request, EntityManagerInterface $manager)
    {
        if ($course) {
           $form=$this->createForm(CourseType::class, $course);
        $form->handleRequest($request);    
        if ($form->isSubmitted() && $form->isValid()) {  
          $data = $request->request;
          
          $course->setTitle($data->get('course')['title'])
                 ->setPicture($data->get('course')['picture'])
                 ->setContent($data->get('course')['content']);

            $manager->persist($course);
            $manager->flush();
            $this->addFlash('success', 'the Course has been edited successfly !');
            return $this->redirectToRoute('prof_profile', ['id'=>$this->getUser()->getId()]);
        }
        } else {
           $course = new Course();
        $form=$this->createForm(CourseType::class, $course);
        $form->handleRequest($request);    
        if ($form->isSubmitted() && $form->isValid()) {  
          
          $course->setCreatedAt(new \DateTime())
                 ->setProf($this->getUser());

            $manager->persist($course);
            $manager->flush();
            $this->addFlash('success', 'the Course has been added successfly !');
            return $this->redirectToRoute('prof_profile', ['id'=>$this->getUser()->getId()]);
        }
        }
        
       

    	return $this->render("prof/course.html.twig",[
    'courseForm'=>$form->createView()
    	]);
    }
    /**
     * @Route("/profile/course/remove/{id}", name="prof_remove_course")
     */
    
    public function removeCourse(Course $course, EntityManagerInterface $maneger)
    {
      $maneger->remove($course);
      $maneger->flush();
      $this->addFlash('success', 'the Course has been elimited successfly !');
      return $this->redirectToRoute('prof_profile', ['id'=>$this->getUser()->getId()]);
    }
    /**
     * @Route("/prof/anounce/edit/{id}", name="prof_edit_anounce")
       * @Route("/prof/anounce/add", name="prof_add_anounce")
     */
    public function anounce(Anounce $anounce=null, Request $request, EntityManagerInterface $manager, AnounceRepository $anounceRepo)
    {
        if ($anounce) {
           $form = $this->createForm(AnounceType::class, $anounce);
           $form->handleRequest($request);
           if ($form->isSubmitted() && $form->isValid()) {  
                 $data = $request->request->get('anounce');
                   $anounce->setTitle($data['title'])
                           ->setContent($data['content']);
                   $manager->persist($anounce);
                   $manager->flush();
            $this->addFlash('success', 'the Anounce has been edited successfly !');
            return $this->redirectToRoute('prof_profile', ['id'=>$this->getUser()->getId()]);
           }
        }else{
          $anounce = new Anounce();
        $form = $this->createForm(AnounceType::class, $anounce);
        $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {  
          
          $anounce->setCreatedAt(new \DateTime())
                 ->setProf($this->getUser());

            $manager->persist($anounce);
            $manager->flush();
            $this->addFlash('success', 'the Anounce has been added successfly !');
            return $this->redirectToRoute('prof_profile', ['id'=>$this->getUser()->getId()]);
        }

        }
        return $this->render("prof/anounce.html.twig", [
        'anounceForm'=>$form->createView()
         ]);
    }
 
     /**
     * @Route("/profile/anounce/remove/{id}", name="prof_remove_anounce")
     */
    
    public function removeAnounce(Anounce $anounce, EntityManagerInterface $maneger)
    {
      $maneger->remove($anounce);
      $maneger->flush();
      $this->addFlash('success', 'the Anounce has been elimited successfly !');
      return $this->redirectToRoute('prof_profile', ['id'=>$this->getUser()->getId()]);
    }

      /**
       * @Route("/prof/note/add", name="prof_add_note")
       * @Route("/prof/note/edit/{id}", name="prof_edit_note")
       */
    public function note(Note $note=null, Request $request
      ,EntityManagerInterface $manager)
    {

      if ($note) {
        $form=$this->createForm(NoteType::class, $note);
        $form->handleRequest($request);    
        if ($form->isSubmitted() && $form->isValid()) {  
             $note->setProf($this->getUser());         
 
             $manager->persist($note);
             $manager->flush();
            $this->addFlash('success', 'the note has been edited successfly !');
            return $this->redirectToRoute('prof_synthese', ['id'=>$this->getUser()->getId()]);
        }
      }elseif(!$note){
           
              $note = new Note();
              $form=$this->createForm(NoteType::class, $note);
              $form->handleRequest($request);    
              if ($form->isSubmitted() && $form->isValid()) {  
    
                $note->setProf($this->getUser());         
                  $manager->persist($note);
                  $manager->flush();
                  $this->addFlash('success', 'the note has been added successfly !');
                  return $this->redirectToRoute('prof_synthese', ['id'=>$this->getUser()->getId()]);
              }
            }

      return $this->render("prof/note.html.twig",[
          'noteForm'=>$form->createView()
      ]);
    }
    
     /**
      * @Route("/prof/synthese/{id}", name="prof_synthese")
      */
    public function synthese(Prof $prof)
    {
     // dd(count($prof->getStudents()));
       $stds = [];
      foreach ($prof->getStudents() as $std) {
        $stds[] = $std->getNotes();
        //dump($std->getNotes());
      }
     
      return $this->render("prof/synthese.html.twig", [
     'notes'=>$stds,
     'stds'=>$prof->getStudents(),
      ]);
    }
}
