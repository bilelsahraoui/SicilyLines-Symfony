<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ReservationRepository;
use App\Repository\TraverseeRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Form\SettingsType;

class CompteController extends AbstractController
{

    public function index(): Response
    {
        return $this->render('compte/index.html.twig');
    }

    public function reservations(ReservationRepository $reservationsRepository, UserInterface $user, TraverseeRepository $traverseeRepository): Response
    {
        $userId = $user->getId();
        $reservations = $reservationsRepository->findBy(["user" => $userId]);
        $travs = $traverseeRepository->findBy(["id" => $reservations]);

        return $this->render('compte/reservations.html.twig',[
            'reservations' => $reservations, 
            'travs' => $travs,
        ]);
    }

    public function reglages(Request $request, ManagerRegistry $doctrine, UserInterface $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        $form = $this->createFormBuilder()
            ->add('username', TextType::class,[
                'required' => true,
                'attr' => [
                    'value' => $user->getUsername(),
                ]
            ])
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'required' => false,
                'first_options' => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Confirmez votre nouveau mot de passe'],
                
            ])
            ->add('Confirmer', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-success',
                    'onclick' => 'return confirm("Voulez vous modifier vos informations?")',
                ]
            ])

            ->getForm()

        ;

        $form->handleRequest($request);

        if ($form->isSubmitted()){

            $data = $form->getData();
            
            $user->setUsername($data['username']);
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $data['password'])
                );    
            
            //Entity Manager 
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('reglages'));
        }
        return $this->render('compte/reglages.html.twig', [
            'reglages' => $form->createView(),
        ]);
    }

}
