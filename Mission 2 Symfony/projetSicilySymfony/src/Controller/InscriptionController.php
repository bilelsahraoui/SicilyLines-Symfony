<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class InscriptionController extends AbstractController
{
    //Fonction pour la route /inscription
    public function index(Request $request, ManagerRegistry $doctrine, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        //Formulaire d'inscription
        $form = $this->createFormBuilder()

            //Champ (Input) de type text
            ->add('username', TextType::class,[
                'required' => true,
            ])

            //Champ (Input) de type email
            ->add('email', EmailType::class,[
                'required' => true,
            ])

            //Champ de type Repeated afin d'avoir 2 champs du même type (ici password)
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmez votre mot de passe']
            ])

            //Champ de type submit 
            ->add('inscription', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-primary'
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

            //Initialisation d'un nouvel utilisateur
            $user = new User();
            $user->setUsername($data['username']); //Affection du username
            $user->setEmail($data['email']); //Affection de l'email
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $data['password']) //Cryptage et affectation du mot de passe
            );

            //Entity Manager 
            $em = $doctrine->getManager();
            //Persistance des données de notre utilisateur
            $em->persist($user);
            //Application de l'ajout
            $em->flush();

            //Redirection à la route connexion
            return $this->redirect($this->generateUrl('connexion'));

        }

        return $this->render('inscription/index.html.twig', [
            'inscription' => $form->createView(), // Création du formulaire avec createView() puis envoi vers la page inscription
        ]);
    }
}
