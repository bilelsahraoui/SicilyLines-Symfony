<?php

namespace App\Controller;

use App\Repository\PortRepository;
use App\Repository\LiaisonRepository;
use App\Entity\Port;
use App\Entity\Liaison;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TraverseeRepository;
use App\Repository\EquipementRepository;
use App\Repository\PeriodeRepository;
use App\Repository\TariferRepository;
use App\Repository\ContenirRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class RechercherController extends AbstractController
{
    //Fonction pour la route /recherche
    public function index(Request $request, ManagerRegistry $doctrine, PortRepository $portRepository, 
    EquipementRepository $equipementRepository): Response
    {

        $portsDepart = $portRepository->ShowPortDepart(); // Récupération des ports de départs
        $portsArrivee = $portRepository->ShowPortArrivee(); // Récupération des ports d'arrivée
        
        //Formulaire de recherche
        $form = $this->createFormBuilder()

            //Champ de type entité, où l'on va récuperer depuis la classe liaison un tableau en effectuant une requête, ici port depart
            ->add('portDepart', EntityType::class, [
                'class' => Liaison::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.portDepart', 'ASC')
                        ->groupBy('l.portDepart');
                },
                'choice_label' => 'portDepart', 
            ])


            //Champ de type entité, où l'on va récuperer depuis la classe liaison un tableau en effectuant une requête, ici port arrivee
            ->add('portArrivee', EntityType::class, [
                'class' => Liaison::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.portArrivee', 'ASC')
                        ->groupBy('l.portArrivee');
                },
                'choice_label' => 'portArrivee',
            ])

            //Champ de type date
            // ->add('date', DateType::class,[
            //     'required' => false,
            //     'widget' => 'choice',
            // ])

            //Champ de type checkbox
            ->add('accesHandicape', CheckboxType::class,[
                'required' => false,
                'label' => 'Accès handicapé',
            ])

            //Champ de type checkbox
            ->add('bar', CheckboxType::class,[
                'required' => false,
                'label' => 'Bar',
            ])

            //Champ de type checkbox
            ->add('pontPromenade', CheckboxType::class,[
                'required' => false,
                'label' => 'Pont promenade',
            ])

            //Champ de type checkbox
            ->add('salonVideo', CheckboxType::class,[
                'required' => false,
                'label' => 'Salon vidéo',
            ])
                
            //Champ de type checkbox
            ->add('rechercher', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
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
            
            //Récuperation des ports depuis $data
            $pDepart = $data["portDepart"];
            $pArrivee = $data["portArrivee"];

            //Query
            $ids = [];

                //Vérif des CheckBoxs, si la case est cochée, alors on ajoute au tableau la valeur 
                if ($data['accesHandicape'] == true){
                    $param1 = 3;
                    array_push($ids, $param1);
                }

                //Vérif des CheckBoxs, si la case est cochée, alors on ajoute au tableau la valeur 
                if ($data['bar'] == true){
                    $param2 = 4;
                    array_push($ids, $param2);
                }

                //Vérif des CheckBoxs, si la case est cochée, alors on ajoute au tableau la valeur 
                if ($data['pontPromenade'] == true){
                    $param3 = 5;
                    array_push($ids, $param3);
                }

                //Vérif des CheckBoxs, si la case est cochée, alors on ajoute au tableau la valeur 
                if ($data['salonVideo'] == true){
                    $param4 = 6;
                    array_push($ids, $param4);
                }
            
                //Vérif du tableau, si vide, on recherche avec tous les critères, sinon par critères
                if(count($ids) == 0){
                    $res = $equipementRepository->findAll();
                }else{
                    $res = $equipementRepository->findBy(["id" => $ids]);
                }
            
            //Render des résultats de la recherche
            return $this->render('rechercher/resultatRecherche.html.twig', [
                'acces' => true, 
                'res' => $res, 
                'pDepart' => $pDepart,
                'pArrivee' => $pArrivee,
            ]);

        }

        //Render du formulaire de recherche
        return $this->render('rechercher/index.html.twig', [
            'portDepart' => $portsDepart, 'portArrivee' => $portsArrivee,
            'recherche' => $form->createView(),
        ]);
    }

    //Fonction proposition 
    public function proposition(Request $request, TraverseeRepository $traverseeRepository, PeriodeRepository $periodeRepository,
    TariferRepository $tariferRepository, ContenirRepository $contenirRepository): Response
    {   
        $idLiaison = $request->get(key:'idLiaison');  //Récuperation de idLiaison avec la méthode get
        $traversees = $traverseeRepository->findBy(["id" =>$idLiaison]); //Recherche des traversées depuis l'idLiaison

        $contenir=$contenirRepository->getCapaciter($traversees[0]->getBateau()->getId()); //Récupere la capacité d'une catégorie
        //d'un bateau depuis son id


        //Formulaire de saisie des caractéristiques
        $form = $this->createFormBuilder()
        
            //Champ de type integer
            ->add('A1', IntegerType::class,[
                'required' => true,
                'label'=> "Adulte",
                'data' => 0, // default value
                'constraints' => array(
                new Assert\NotBlank(),  
                new Assert\Range(array('min' => 0, 'max' => $contenir[0]['nbMax'], 
                'minMessage' =>"Impossible d'enregister une valeur negative", 
                'maxMessage' => "Capacité maximale pour cette catégorie : " . $contenir[0]['nbMax'] ." ") )
                )
                ])

            //Champ de type integer
            ->add('A2', IntegerType::class,[
                'required' => true,
                'label'=> "Junior 8 à 18 ans",
                'data' => 0, // default value
                'constraints' => array(
                new Assert\NotBlank(),  
                new Assert\Range(array('min' => 0, 'max' => $contenir[0]['nbMax'], 
                'minMessage' =>"Impossible d'enregister une valeur negative", 
                'maxMessage' => "Capacité maximale pour cette catégorie : " . $contenir[0]['nbMax'] ." ") )
                )
                ])

            //Champ de type integer
            ->add('A3', IntegerType::class,[
                'required' => true,
                'label'=> "Enfant 0 à 7 ans",
                'data' => 0, // default value
                'constraints' => array(
                new Assert\NotBlank(),  
                new Assert\Range(array('min' => 0, 'max' => $contenir[0]['nbMax'], 
                'minMessage' =>"Impossible d'enregister une valeur negative", 
                'maxMessage' => "Capacité maximale pour cette catégorie : " . $contenir[0]['nbMax'] ." ") )
                )
                ])

            //Champ de type integer
            ->add('B1', IntegerType::class,[
                'required' => true,
                'label'=> "Voiture long.inf.4m",
                'data' => 0, // default value
                'constraints' => array(
                new Assert\NotBlank(),  
                new Assert\Range(array('min' => 0, 'max' => $contenir[1]['nbMax'], 
                'minMessage' =>"Impossible d'enregister une valeur negative", 
                'maxMessage' => "Capacité maximale pour cette catégorie : " . $contenir[1]['nbMax'] ." ") )
                )
                ])

            //Champ de type integer
            ->add('B2', IntegerType::class,[
                'required' => true,
                'label'=> "Voiture long.inf.5m",
                'data' => 0, // default value
                'constraints' => array(
                new Assert\NotBlank(),  
                new Assert\Range(array('min' => 0, 'max' => $contenir[1]['nbMax'], 
                'minMessage' =>"Impossible d'enregister une valeur negative", 
                'maxMessage' => "Capacité maximale pour cette catégorie : " . $contenir[1]['nbMax'] ." ") )
                )
                ])

            //Champ de type integer
            ->add('C1', IntegerType::class,[
                'required' => true,
                'label'=> "Fourgon",
                'data' => 0, // default value
                'constraints' => array(
                new Assert\NotBlank(),  
                new Assert\Range(array('min' => 0, 'max' => $contenir[2]['nbMax'], 
                'minMessage' =>"Impossible d'enregister une valeur negative", 
                'maxMessage' => "Capacité maximale pour cette catégorie : " . $contenir[2]['nbMax'] ." ") )
                )
                ])

            //Champ de type integer
            ->add('C2', IntegerType::class,[
                'required' => true,
                'label'=> "Camping Car",
                'data' => 0, // default value
                'constraints' => array(
                new Assert\NotBlank(),  
                new Assert\Range(array('min' => 0, 'max' => $contenir[2]['nbMax'], 
                'minMessage' =>"Impossible d'enregister une valeur negative", 
                'maxMessage' => "Capacité maximale pour cette catégorie : " . $contenir[2]['nbMax'] ." ") )
                )
                ])

            //Champ de type integer
            ->add('C3', IntegerType::class,[    
                'required' => true,
                'label'=> "Camion",
                'data' => 0, // default value
                'constraints' => array(
                new Assert\NotBlank(),  
                new Assert\Range(array('min' => 0, 'max' => $contenir[2]['nbMax'], 
                'minMessage' =>"Impossible d'enregister une valeur negative", 
                'maxMessage' => "Capacité maximale pour cette catégorie : " . $contenir[2]['nbMax'] ." ") )
                )
                ])

            //Champ de type submit
            ->add('Sauvegarder', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-success',
                ]
                ])


            //Récuperation du formulaire
            ->getForm()

        ;

        //Récuperation de la requête
        $form->handleRequest($request);

        //Vérification de si le formulaire est envoyé
        if ($form->isSubmitted()){

            //Récuperation des données du formulaire
            $data = $form->getData();
        
            $nbA1 = $data['A1'];
            $nbA2 = $data['A2'];
            $nbA3 = $data['A3'];
            $nbB1 = $data['B1'];
            $nbB2 = $data['B2'];
            $nbC1 = $data['C1'];
            $nbC2 = $data['C2'];
            $nbC3 = $data['C3'];
            
            


            //Si les données saisies sont < à 1 (vides)
            if (($nbA1+$nbA2+$nbA3+$nbB1+$nbB2+$nbC1+$nbC2+$nbC3)<1){

                //Retourne une erreur
                $error = "Veuillez remplir au moins un champ !";
                return $this->render('rechercher/resultat.html.twig', [
                    'error'=>$error,
                    'traversees' => $traversees, 'idLiaison' => $idLiaison,
                    'form' => $form->createView(),
                ]);
            }

            //Récuperation de l'id de la liaison depuis notre traversée
            $liaison_id=$traversees[0]->getLiaison()->getId();
            //Récuperation de la periode de notre traversée
            $periode=$periodeRepository->getPeriode($traversees[0]->getDate());
            
            //Récuperation des tarifs
            $listTarif=$tariferRepository->getTarifTraversee($liaison_id, $periode[0]->getId());
            
            //Calcul du tarif total
            $tarifTotal=$nbA1*$listTarif[0]['tarif']+$nbA2*$listTarif[1]['tarif']+$nbA3*$listTarif[2]['tarif']+
            $nbB1*$listTarif[3]['tarif']+$nbB2*$listTarif[4]['tarif']+$nbC1*$listTarif[5]['tarif']+
            $nbC2*$listTarif[6]['tarif']+$nbC3*$listTarif[7]['tarif'];

            
            return $this->render('rechercher/resultat.html.twig', [
                'nbA1' => $nbA1, 'nbA2' => $nbA2, 'nbA3' => $nbA3, 'nbB1' => $nbB1, 
                'nbB2' => $nbB2, 'nbC1' => $nbC1, 'nbC2' => $nbC2, 'nbC3' => $nbC3, 
                'traversees' => $traversees, 'idLiaison' => $idLiaison, 'tarifTotal'=>$tarifTotal,
                'form' => $form->createView(), 'recap'=>true,
            ]);

        }
        

        return $this->render('rechercher/resultat.html.twig', [
            'traversees' => $traversees, 'idLiaison' => $idLiaison,
            'form' => $form->createView(),
        ]);
    }


    //Fonction recherchant toutes les liaisons possibles
    public function searchAll(EquipementRepository $equipementRepository): Response
    {
        $res = $equipementRepository->findAll(); //Récuperation de toutes les liaisons

            return $this->render('rechercher/searchAll.html.twig', [ 
                'res' => $res,
                'acces' => true,
            ]);

    }

}
