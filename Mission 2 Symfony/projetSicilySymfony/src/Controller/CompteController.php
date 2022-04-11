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

    //Fonction pour la route /compte
    public function index(): Response
    {
        return $this->render('compte/index.html.twig');
    }

    //Fonction pour la route /reservations
    public function reservations(ReservationRepository $reservationsRepository, UserInterface $user, TraverseeRepository $traverseeRepository): Response
    {
        $userId = $user->getId(); //Récupération de l'id de notre utilisateur connecté
        $reservations = $reservationsRepository->findBy(["user" => $userId]); // Récupération des réservation de notre utilisateur
        $travs = $traverseeRepository->findBy(["id" => $reservations]); // Récupération des traversées des réservations de notre utilisateur

        return $this->render('compte/reservations.html.twig',[
            'reservations' => $reservations, //Passage des reservations en paramètre du render
            'travs' => $travs, //Passage des traversées en paramètre du render
        ]);
    }

    //Fonction pour la route /reglages
    public function reglages(Request $request, ManagerRegistry $doctrine, UserInterface $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        //Formulaire d'actualisation des infos de l'utilisateur
        $form = $this->createFormBuilder()

            //Champ (Input) de type texte ayant pour valeur l'username de l'utilisateur
            ->add('username', TextType::class,[
                'required' => true,
                'attr' => [
                    'value' => $user->getUsername(),
                ]
            ])

            //Champ de type Repeated afin d'avoir 2 champs du même type (ici password)
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'required' => false,
                'first_options' => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Confirmez votre nouveau mot de passe'],
                
            ])

            //Champ de type submit afin d'envoyer les données du formulaire
            ->add('Confirmer', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-success',
                    'onclick' => 'return confirm("Voulez vous modifier vos informations?")', //Demande de confirmation onclick
                ]
            ])

            //Formation du formulaire
            ->getForm()

        ;

        //Récupération de la requête envoyée
        $form->handleRequest($request);

        //Vérification si le formulaire à été submit 
        if ($form->isSubmitted()){

            //Variable data récupère les données saisie dans le formulaire
            $data = $form->getData();

            //Actualisation du username de l'utilisateur
            $user->setUsername($data['username']);

            //Récupération & Cryptage du password saisi
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $data['password'])
                );    
            
            //Entity Manager 
            $em = $doctrine->getManager();
            //Persistance des données de notre utilisateur
            $em->persist($user);
            //Application des changements
            $em->flush();

            //Redirection à la page reglage
            return $this->redirect($this->generateUrl('reglages'));
        }

        return $this->render('compte/reglages.html.twig', [
            'reglages' => $form->createView(), // Création du formulaire avec createView() puis envoi vers la page reglages
        ]);
    }

}
