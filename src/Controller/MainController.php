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


        $campusRepository = $this->getDoctrine()->getRepository(Campus::class);
        $campus = $campusRepository->findAll();




        $ListFormSortie = new ListFormSortie();
        $sortieForm = $this->createForm(ListSortieType::class,$ListFormSortie);
        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted()){



                $userLog = $this->getUser();


                $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
                $sorties = $sortieRepository->findByFormFilter($ListFormSortie, $userLog);

                return $this->render('Page/home.html.twig',["sorties"=>$sorties,"campus"=>$campus,"sortieForm"=>$sortieForm->createView()]);
            }else{
                $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
                $sorties = $sortieRepository->findAll();

                return $this->render('Page/home.html.twig',["sorties"=>$sorties,"campus"=>$campus,"sortieForm"=>$sortieForm->createView()]);
            }


        }



}
