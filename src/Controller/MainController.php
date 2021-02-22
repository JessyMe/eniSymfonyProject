<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Repository\InscriptionRepository;
use App\Service\ListFormSortie;
use App\Entity\Campus;
use App\Entity\Sortie;
use App\Form\ListSortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function List(Request $request)
    {

        //Liste Campus pour menu déroulant
        $campusRepository = $this->getDoctrine()->getRepository(Campus::class);
        $campus = $campusRepository->findAll();
        //création du formulaire
        $ListFormSortie = new ListFormSortie();
        $sortieForm = $this->createForm(ListSortieType::class,$ListFormSortie);
        $sortieForm->handleRequest($request);
        //Liste des inscriptions
        $InscriptionRepository = $this->getDoctrine()->getRepository(Inscription::class);
        $inscriptions = $InscriptionRepository->findAll();
        dump($inscriptions);
        dump($this->getUser());
        if($sortieForm->isSubmitted()){
                $userLog = $this->getUser();
                $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
                $sorties = $sortieRepository->findByFormFilter($ListFormSortie, $userLog);

                return $this->render('Page/home.html.twig',
                    ["user"=>$userLog,
                        "inscriptions"=>$inscriptions,
                        "sorties"=>$sorties,
                        "campus"=>$campus,
                        "sortieForm"=>$sortieForm->createView()]);
        }else{
                $userLog = $this->getUser();
                $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
                $sorties = $sortieRepository->findAll();

                return $this->render('Page/home.html.twig',
                    ["user"=>$userLog,
                        "inscriptions"=>$inscriptions,
                        "sorties"=>$sorties,
                        "campus"=>$campus,
                        "sortieForm"=>$sortieForm->createView()]);
            }


        }



}
