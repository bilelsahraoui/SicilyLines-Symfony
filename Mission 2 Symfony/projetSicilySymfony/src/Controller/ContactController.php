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

    //Fonction pour la route /contact
    public function Contact_Recla_Question(ReservationRepository $reservationsRepository, TraverseeRepository $traverseeRepository, Request $request): Response
    {

        //Formulaire d'envoi de question ou reclamation
        $form = $this->createFormBuilder()

        //Champ (Input) de type email
        ->add('mail', EmailType::class,[
            'required' => true,
            'label' => "Entrez votre adresse e-mail : ",
            'attr' => ['class' => 'form-control',
            'style' => 'width: 300px']])

        //Champ de type select parmis des paramètres donnés
        ->add('QuestionOrRecla',ChoiceType::class,
            ['choices' => [
            'Question' => '1',
            'Reclamation' => '2'],
            'required' => true,
            'label' => "Question ou Reclamation :", 
            'multiple'=>false,'expanded'=>true])

        //Champ (Input) de type text area
        ->add('contenu', TextareaType::class, [
            'label' => "Ecrivez ici !",
            'required' => true,
            'attr' => array('cols' => '5', 'rows' => '5')])
        
        //Champ de type submit 
        ->add('Envoyer',SubmitType::class)

        //Formation du formulaire
        ->getForm();

        //Récupération de la requête envoyée
        $form->handleRequest($request);

        //Vérification si le formulaire à été submit 
        if($form->isSubmitted()){

            //Variable data récupère les données saisie dans le formulaire
            $data=$form->getData();

            //Création d'une variable stockant un message d'alerte
            $alert = "Message bien envoyé !";
            return $this->redirectToRoute('contact',['alert'=>$alert]);
        }
            
        return $this->render('contact/index.html.twig',[
            'contacter' => $form->createView(), // Création du formulaire avec createView() puis envoi vers la page contacter
        ]);

    }

}
