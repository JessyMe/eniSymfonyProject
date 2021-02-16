<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\ListSortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function List(Request $request): Response
    {
        $sortie = new Sortie();
        $sortieForm = $this->createForm(ListSortieType::class,$sortie);
        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $sortieForm->handleRequest($request);







            return $this->render('Page/home.html.twig');
        }else{
            $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
            $sorties = $sortieRepository->findAll();
            return $this->render('Page/home.html.twig',["sortie" => $sorties] );
        }


    }
}
