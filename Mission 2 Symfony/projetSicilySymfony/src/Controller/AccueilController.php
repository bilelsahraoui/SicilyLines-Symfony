<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TraverseeRepository;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AccueilController extends AbstractController
{

    //Fonction pour la route / ou /accueil
    public function index(TraverseeRepository $traverseeRepository): Response
    {
        $traversees = $traverseeRepository->findAll(); //Récupération de toutes les traversées

        return $this->render('accueil/accueil.html.twig', [
            'traversees' => $traversees, //Passage des traversées en paramètre du render
        ]);
    }

}
