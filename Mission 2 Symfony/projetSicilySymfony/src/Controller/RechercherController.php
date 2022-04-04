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

    public function index(Request $request, ManagerRegistry $doctrine, 
    PortRepository $portRepository, EquipementRepository $equipementRepository 
    ): Response
    {
        $portsDepart = $portRepository->ShowPortDepart();
        $portsArrivee = $portRepository->ShowPortArrivee();
        
        //Formulaire de recherche

        $form = $this->createFormBuilder()
            ->add('portDepart', EntityType::class, [
                'class' => Liaison::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.portDepart', 'ASC')
                        ->groupBy('l.portDepart');
                },
                'choice_label' => 'portDepart',
            ])
            ->add('portArrivee', EntityType::class, [
                'class' => Liaison::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.portArrivee', 'ASC')
                        ->groupBy('l.portArrivee');
                },
                'choice_label' => 'portArrivee',
            ])
            ->add('date', DateType::class,[
                'required' => false,
                'widget' => 'choice',
                
            ])
            ->add('accesHandicape', CheckboxType::class,[
                'required' => false,
                'label' => 'Accès handicapé',
            ])
            ->add('bar', CheckboxType::class,[
                'required' => false,
                'label' => 'Bar',
            ])
            ->add('pontPromenade', CheckboxType::class,[
                'required' => false,
                'label' => 'Pont promenade',
            ])
            ->add('salonVideo', CheckboxType::class,[
                'required' => false,
                'label' => 'Salon vidéo',
            ])
            ->add('rechercher', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
            ])

            ->getForm()

        ;

        $form->handleRequest($request);

        if ($form->isSubmitted()){

            $data = $form->getData();
            
            //Ports
            $pDepart = $data["portDepart"];
            $pArrivee = $data["portArrivee"];

            //Query
            
            $ids = [];

                //CheckBoxs
                if ($data['accesHandicape'] == true){
                    $param1 = 3;
                    array_push($ids, $param1);
                }
                if ($data['bar'] == true){
                    $param2 = 4;
                    array_push($ids, $param2);
                }
                if ($data['pontPromenade'] == true){
                    $param3 = 5;
                    array_push($ids, $param3);
                }
                if ($data['salonVideo'] == true){
                    $param4 = 6;
                    array_push($ids, $param4);
                }
            
                if(count($ids) == 0){
                    $res = $equipementRepository->findAll();
                }else{
                    $res = $equipementRepository->findBy(["id" => $ids]);
                }
            
            return $this->render('rechercher/resultatRecherche.html.twig', [
                'acces' => true, 
                'res' => $res, 
                'pDepart' => $pDepart,
                'pArrivee' => $pArrivee,
            ]);

        }


        return $this->render('rechercher/index.html.twig', [
            'portDepart' => $portsDepart, 'portArrivee' => $portsArrivee,
            'recherche' => $form->createView(),
        ]);
    }

    public function proposition(Request $request, TraverseeRepository $traverseeRepository, 
    PeriodeRepository $periodeRepository, TariferRepository $tariferRepository, 
    ContenirRepository $contenirRepository): Response
    {   
        $idLiaison = $request->get(key:'idLiaison');
        $traversees = $traverseeRepository->findBy(["id" =>$idLiaison]);

        $contenir=$contenirRepository->getCapaciter($traversees[0]->getBateau()->getId());


        $form = $this->createFormBuilder()
        
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

            ->add('Sauvegarder', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-success',
                ]
                ])


            ->getForm()

        ;

        $form->handleRequest($request);

        if ($form->isSubmitted()){

            $data = $form->getData();
        
            $nbA1 = $data['A1'];
            $nbA2 = $data['A2'];
            $nbA3 = $data['A3'];
            $nbB1 = $data['B1'];
            $nbB2 = $data['B2'];
            $nbC1 = $data['C1'];
            $nbC2 = $data['C2'];
            $nbC3 = $data['C3'];
            
            


            if (($nbA1+$nbA2+$nbA3+$nbB1+$nbB2+$nbC1+$nbC2+$nbC3)<1){
                $error = "Veuillez remplir au moins un champ !";
                return $this->render('rechercher/resultat.html.twig', [
                    'error'=>$error,
                    'traversees' => $traversees, 'idLiaison' => $idLiaison,
                    'form' => $form->createView(),
                ]);
            }

            $liaison_id=$traversees[0]->getLiaison()->getId();
            $periode=$periodeRepository->getPeriode($traversees[0]->getDate());


            
            $listTarif=$tariferRepository->getTarifTraversee($liaison_id, $periode[0]->getId());


            
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


    public function searchAll(EquipementRepository $equipementRepository): Response
    {
        $res = $equipementRepository->findAll();

            return $this->render('rechercher/searchAll.html.twig', [ 
                'res' => $res,
                'acces' => true,
            ]);

    }

}
