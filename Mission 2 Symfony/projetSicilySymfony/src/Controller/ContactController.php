<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReservationRepository;
use App\Repository\TraverseeRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

class ContactController extends AbstractController
{

    public function index(): Response
    {
        return $this->render('contact/index.html.twig');
    }

    public function social(): Response
    {
        return $this->render('contact/social.html.twig');
    }

    public function Contact_Recla_Question(ReservationRepository $reservationsRepository, TraverseeRepository $traverseeRepository, Request $request): Response
    {

    //     //Form de base
    //     $form = $this->createFormBuilder()
    //     ->add('choix', ChoiceType::class,[
    //         'required' => true,
    //         'label' => "Choisir un mode d'authentification",
    //         'choices' => [
    //             'Numéro de téléphone' => 1,
    //             'Adresse e-mail' => 2,
    //         ], 
    //         'attr' => ['class' => 'form-control',
    //             'style' => 'width: 300px']
    //     ])
    //     ->add('Confirmer', SubmitType::class,[
    //         'label' => "J'ai choisi !",
    //         'attr' => [
    //             'class' => 'btn btn-primary',
    //         ]
    //     ])

    //     ->getForm()

    // ;

    // $form->handleRequest($request);

    // if ($form->isSubmitted()){

    //     $data = $form->getData();

    //     //Form si choix = tél
    //     if($data['choix'] == 1){
    //         $form = $this->createFormBuilder()
    //     ->add('choix', ChoiceType::class,[
    //         'required' => true,
    //         'label' => "Choisir un mode d'authentification",
    //         'disabled' => true,
    //         'choices' => [
    //             'Numéro de téléphone' => 1,
    //         ], 
    //         'attr' => ['class' => 'form-control',
    //             'style' => 'width: 300px']
    //     ])
    //     ->add('codeResa', TextType::class,[
    //         'required' => true,
    //         'label' => "Entrez votre code de réservation", 
    //         'attr' => ['class' => 'form-control',
    //             'style' => 'width: 300px']
    //     ])
    //     ->add('num', TextType::class,[
    //         'required' => true,
    //         'label' => "Entrez votre numéro de téléphone", 
    //         'attr' => ['class' => 'form-control',
    //             'style' => 'width: 300px']
    //     ])
    //     ->add('Confirmer', SubmitType::class,[
    //         'label' => "J'accède à mon espace !",
    //         'attr' => [
    //             'class' => 'btn btn-primary',
    //         ]
    //     ])

    //     ->getForm()

    //     ;

    //     return $this->render('contact/index.html.twig',[
    //         'contact' => $form->createView(), 
    //         'conf' => true,
    //     ]);

    //     //Form si choix = mail
    //     }else{
    //         $form = $this->createFormBuilder()
    //     ->add('choix', ChoiceType::class,[
    //         'required' => true,
    //         'label' => "Choisir un mode d'authentification",
    //         'disabled' => true,
    //         'choices' => [
    //             'Adresse e-mail' => 2,
    //         ], 
    //         'attr' => ['class' => 'form-control',
    //             'style' => 'width: 300px']
    //     ])
    //     ->add('codeResa', TextType::class,[
    //         'required' => true,
    //         'label' => "Entrez votre code de réservation", 
    //         'attr' => ['class' => 'form-control',
    //             'style' => 'width: 300px']
    //     ])
    //     ->add('mail', EmailType::class,[
    //         'required' => true,
    //         'label' => "Entrez votre adresse e-mail", 
    //         'attr' => ['class' => 'form-control',
    //             'style' => 'width: 300px']
    //     ])
    //     ->add('Confirmer', SubmitType::class,[
    //         'label' => "J'accède à mon espace !",
    //         'attr' => [
    //             'class' => 'btn btn-primary',
    //         ]
    //     ])

    //     ->getForm()

    //     ;

    //     return $this->render('contact/index.html.twig',[
    //         'contact' => $form->createView(), 
    //         'conf' => true,
    //     ]);
    //     }

        
    // }
    $form = $this->createFormBuilder()

    ->add('mail', EmailType::class,[
        'required' => true,
        'label' => "Entrez votre adresse e-mail : ",
        'attr' => ['class' => 'form-control',
        'style' => 'width: 300px']])

    ->add('QuestionOrRecla',ChoiceType::class,
        ['choices' => [
        'Question' => '1',
        'Reclamation' => '2'],
        'required' => true,
        'label' => "Question ou Reclamation :", 
        'multiple'=>false,'expanded'=>true])

    ->add('contenu', TextareaType::class, [
        'label' => "Ecrivez ici !",
        'required' => true,
        'attr' => array('cols' => '5', 'rows' => '5')])
    
    ->add('Envoyer',SubmitType::class)

    ->getForm();

    $form->handleRequest($request);


    if($form->isSubmitted()){
        $data=$form->getData();

        $alert = "Message bien envoyé !";
        return $this->redirectToRoute('contact',['alert'=>$alert]);
    }
        
return $this->render('contact/index.html.twig',[
'contacter' => $form->createView(),
        ]);

    }

}
