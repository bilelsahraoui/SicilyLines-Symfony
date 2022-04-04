<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Reservation;
use App\Entity\Traversee;
use App\Entity\Participer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReservationRepository;
use App\Repository\TraverseeRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;

class ReserverController extends AbstractController
{

    public function index(Request $request, ReservationRepository $reserverRepository, ManagerRegistry $doctrine,
    TraverseeRepository $traverseeRepository): Response
    {  
        $id = $request->request->get(key:'id');
        $tarifTot = $request->request->get(key:'tarifTotal');

        $formClient = $this->createFormBuilder()
            ->add('creditCard', TextType::class,[
                'label' => 'Carte de crédit',
                'required' => false,
                'attr' => ['class' => 'form-control',
                'style' => 'width: 300px']
            ])
            ->add('cvv', TextType::class,[
                'label' => 'CVV',
                'required' => false,
                'attr' => ['class' => 'form-control',
                'style' => 'width: 300px']
            ])
            ->add('identity', TextType::class,[
                'label' => 'Nom sur la carte de crédit',
                'required' => false,  
                'attr' => ['class' => 'form-control',
                'style' => 'width: 300px']
            ])
            ->add('tel', TextType::class,[
                'label' => 'Entrez votre numéro de téléphone',
                'required' => true,  
                'attr' => ['class' => 'form-control',
                    'style' => 'width: 300px'],
                'constraints' => array(
                    new Assert\NotBlank(),  
                    new Assert\Range(array('min' => 10, 'max' => 10, 
                    'minMessage' =>"Enregistrez un numéro valide", 
                    'maxMessage' => "Enregistrez un numéro valide",
                    
                    )))
                ])
            ->add('mail', EmailType::class,[
                'label' => 'Entrez votre e-mail',
                'required' => true,  
                'attr' => ['class' => 'form-control',
                    'style' => 'width: 300px']
                ])
            ->add('idTrav', HiddenType::class,[
                'attr' => [
                    'value' => $id,
                ]
            ])
            ->add('Confirmer', SubmitType::class,[
                'label' => 'Payer',
                'attr' => [
                    'class' => 'btn btn-success',
                    'onclick' => 'return confirm("êtes vous sûr?")',
                ]
            ])

            ->getForm();
            
        $formClient->handleRequest($request);
        
        if ($formClient->isSubmitted()){

            $data = $formClient->getData();

            //flush

                //data
                if($data["tel"]){

                    $tel = $data["tel"];
                    $mail = $data["mail"];

                    //Traversee
                    $traversee = $traverseeRepository->findBy(["id" => $data["idTrav"]]);

                    
                    //Participer
                    $participer = new Participer();



                    //Reservation
                    $code = $reserverRepository->getRandomString(10);
                    $reservation = new Reservation();
                    $reservation->setCodeReservation($code);
        

                    //Client
                    $client = new Client();
                    $client->setTel($tel);
                    $client->setMail($mail);
                    $client->addReservation($reservation);
                    

                    //Entity Manager flush client
                    $em = $doctrine->getManager();
                    $em->persist($client);
                    $em->flush();
                    
                    
                    //Entity Manager flush reservation
                    $reservation->setClient($client);
                    $reservation->setTraversee($traversee[0]);
                    $em->persist($reservation);
                    $em->flush();

                    //Entity Manager flush participer
                    
                    
                    
                    }
                    else{
                        $user = $this->get('security.token_storage')->getToken()->getUser();
                        if($user){

                        
                        //Traversee
                        $traversee = $traverseeRepository->findBy(["id" => $data["idTrav"]]);


                        //Reservation
                        $code = $reserverRepository->getRandomString(10);
                        $reservation = new Reservation();
                        $reservation->setCodeReservation($code);


                        //User
                        $user->addReservation($reservation);
                        
                        
                        //Entity Manager flush reservation
                        $em = $doctrine->getManager();
                        $reservation->setUser($user);
                        $reservation->setTraversee($traversee[0]);
                        $em->persist($reservation);
                        $em->flush();

                    }
                }
                    $confirm = "Vous avez bien réservé cette traversée ! Votre code de réservation est : ".$code.".";

                return $this->render("reserver/validation.html.twig", [
                    'confirm' => $confirm,
                ]);
        };

        $form = $this->createFormBuilder()
            ->add('creditCard', TextType::class,[
                'label' => 'Carte de crédit',
                'required' => false,
                'attr' => ['class' => 'form-control',
                'style' => 'width: 300px']
            ])
            ->add('cvv', TextType::class,[
                'label' => 'CVV',
                'required' => false,
                'attr' => ['class' => 'form-control',
                'style' => 'width: 300px']
            ])
            ->add('identity', TextType::class,[
                'label' => 'Nom sur la carte de crédit',
                'required' => false,  
                'attr' => ['class' => 'form-control',
                'style' => 'width: 300px']
            ])
            ->add('idTrav', HiddenType::class,[
                'attr' => [
                    'value' => $id,
                ]
            ])
            ->add('Confirmer', SubmitType::class,[
                'label' => 'Payer',
                'attr' => [
                    'class' => 'btn btn-success',
                    'onclick' => 'return confirm("êtes vous sûr?")',
                ]
            ])

            ->getForm();
            ;

        return $this->render('reserver/index.html.twig', [
            'payement' => $form->createView(),
            'payementClient' => $formClient->createView(),
            'tarifTotal' => $tarifTot,
            
        ]);
        
    }

}   

