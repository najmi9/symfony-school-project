<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StdPerInfoRepository;
use App\Entity\StdPerInfo;
use App\Form\StdPerInfoType;
use App\Entity\StdCv;
use App\Form\StdCvType;
use App\Entity\StdChoice;
use App\Form\StdChoiceType;
use App\Entity\StdProfile;
use App\Form\StdProfileType;
use App\Entity\Student;



class InscriptionController extends AbstractController
{
    /**
     * @Route("/student/inscription", name="inscription")
     */
    public function index(Request $request, EntityManagerInterface $manager
    , UserPasswordEncoderInterface $passwordEncoder)
    {
       
              $std = new StdPerInfo();
              $finStd = new Student();
       
              $form = $this->createForm(StdPerInfoType::class, $std);
              $form->handleRequest($request);
       
              if ($form->isSubmitted() && $form->isValid()) {
                  dd($request->request);
                 // encode the plain password
                   $std->setPassword(
                       $passwordEncoder->encodePassword(
                           $std,
                           $form->get('password')->getData()
                       )
                   );
       
                  $finStd->setStdperinfo($std);
                   $manager->persist($std);
                  $manager->persist($finStd);
                   $manager->flush();
                   
                   $this->addFlash('message', 'Ok, You are added successfly, Please Verify your email to continue !');
                   return $this->redirectToRoute('home');
              }
        return $this->render('inscription/index.html.twig', [
           'stdForm'=>$form->createView()
        ]);
    }


     /**
     * @Route("/student/inscription/edit/{id}", name="inscription_edit")
     */
    public function editInscription($id, Request $request, 
      EntityManagerInterface $manager
    , UserPasswordEncoderInterface $passwordEncoder, StdPerInfoRepository $stdRepo)
    {
       
             $std = $stdRepo->findOneById($id);
            
       
              $form = $this->createForm(StdPerInfoType::class, $std);
              $form->handleRequest($request);
       
              if ($form->isSubmitted() && $form->isValid()) {
                  dd($request->request);

                //la modification des informations et de Vérification de l'email

                 // encode the plain password
                   $std->setPassword(
                       $passwordEncoder->encodePassword(
                           $std,
                           $form->get('password')->getData()
                       )
                   );
       
                  $finStd->setStdperinfo($std);
                   $manager->persist($std);
                  $manager->persist($finStd);
                   $manager->flush();
                   
                   $this->addFlash('message', 'Ok, Your Information edited successfly, Please Verify your email to continue !');
                   return $this->redirectToRoute('home');
              }
        return $this->render('inscription/index.html.twig', [
           'stdForm'=>$form->createView()
        ]);
    }

     
    /**
     * @IsGranted("ROLE_USER")
     * @Route("student/cursur-accademique/{id}", name="cv_student")
     */
    
    public function studentCv(Request $request, StdPerInfo $std, EntityManagerInterface $manager)
    {

     $finStd = $manager->getRepository(Student::class)->findOneByStdperinfo($std);

    	if ($finStd->getStdcv()) {

        $stdCv = $finStd->getStdcv();

          $form = $this->createForm(StdCvType::class, $stdCv);
           $form->handleRequest($request);
             if ($form->isSubmitted() && $form->isValid()) {

              $day = $request->request->get('std_cv')['year']['day'];
              $month = $request->request->get('std_cv')['year']['month'];
              $year = $request->request->get('std_cv')['year']['year'];
              $date = $day.'-'.$month.'-'.$year;
                 $stdCv->setLevel($request->request->get('std_cv')['level'])
                       ->setSchool($request->request->get('std_cv')['school'])
                       ->setYear(date_create($date))
                       ->setDiplomes($request->request->get('std_cv')['diplomes'])
                  ;     
                  $finStd->setStdcv($stdCv);
      
                  $manager->persist($stdCv);
                  $manager->persist($finStd);
                  $manager->flush();

                  $this->addFlash('message', 'Ok, Your Cv edited successfly!');
                  return $this->redirectToRoute('profile', ['id'=>$finStd->getId()]);      
             }

      }else{
            $stdCv = new StdCv();
      
             $form = $this->createForm(StdCvType::class, $stdCv);
             $form->handleRequest($request);
      
             if ($form->isSubmitted() && $form->isValid()) {
                 
                  $finStd->setStdcv($stdCv);
      
                  $manager->persist($stdCv);
                  $manager->persist($finStd);
                  $manager->flush();
                  $this->addFlash('message', 'Ok, You are added successfly!');
                  return $this->redirectToRoute('profile', ['id'=>$finStd->getId()]);
      
             }
           }

        return $this->render('inscription/cv.html.twig', [
           'stdCvForm'=>$form->createView()
        ]);
    }

     /**
     * @IsGranted("ROLE_USER")
     * @Route("/student/choice/{id}", name="choice_student")
     */
    
    public function studentChoice(Request $request, StdCv $stdCv, EntityManagerInterface $manager)
    {
      $finStd = $manager->getRepository(Student::class)->findOneByStdcv($stdCv);
      if ($finStd->getStdchoice()) {
       $stdChoice = $finStd->getStdchoice();
         $form = $this->createForm(StdChoiceType::class, $stdChoice);
             $form->handleRequest($request);
      
             if ($form->isSubmitted() && $form->isValid()) {
            
                $choice = $request->request->get('std_choice')['bactype'];
                 $stdChoice->setBactype($choice);
                 $finStd->setStdchoice($stdChoice);
                  $manager->persist($stdChoice);
                 $manager->persist($finStd);
                  $manager->flush();
                  $this->addFlash('message', 'Ok, Your Choice edited successfly!');
                 return $this->redirectToRoute('profile', ['id'=>$finStd->getId()]);
             }

      }else{
        $stdChoice = new StdChoice();
             $form = $this->createForm(StdChoiceType::class, $stdChoice);
             $form->handleRequest($request);
      
             if ($form->isSubmitted() && $form->isValid()) {
                  
                 $finStd->setStdchoice($stdChoice);
                  $manager->persist($stdChoice);
                 $manager->persist($finStd);
                  $manager->flush();
                  $this->addFlash('message', 'Ok, You are added successfly!');
                 return $this->redirectToRoute('profile', ['id'=>$finStd->getId()]);
             }
      }

        return $this->render('inscription/choice_student.html.twig', [
           'stdChoiceForm'=>$form->createView()
        ]);
    }

     /**
     * @IsGranted("ROLE_USER")
     * @Route("compte/student/{id}", name="profile_student")
     */
    
    public function studentProfile(StdChoice $stdPerInfo, EntityManagerInterface $manager)
    {
      $finStd = $manager->getRepository(Student::class)->findOneByStdchoice($stdPerInfo);

    	$stdProfile = new StdProfile();

      $stdProfile->setState('TODO')
      ->setNumber('TODO')
      ->setNotes(0);
      $finStd->setProfile($stdProfile);
      $manager->persist($finStd);
      $manager->persist($stdProfile);
      $manager->flush();

  
      
        return $this->redirectToRoute('profile', ['id'=>$stdPerInfo->getId()]);
        //return $this->render('inscription/profile.html.twig',[
  // 'res'=>$finStd
      //  ]);
    }
}
