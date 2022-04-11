<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    
    //Fonction pour la route /login
    public function index(): Response
    {
        return $this->render('connexion/index.html.twig');
    }
    
}
