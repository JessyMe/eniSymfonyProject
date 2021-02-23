<?php

namespace App\Controller;

//use App\Entity\Etat;
use App\Entity\Inscription;
use App\Service\ListFormSortie;
use App\Entity\Campus;
use App\Entity\Sortie;
use App\Form\ListSortieType;
//use Doctrine\ORM\EntityManagerInterface;
//use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/home", name="main_home")
     */
    public function List(Request $request)
    {
       // $this->setEtatSortie();
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

        public function setEtatSortie()
        {
            $em = $this->getDoctrine()->getManager();
            //mise à jour état ouverte -> en cours ou cloturée
            $date = new \DateTime();
            $etat = 2;
            $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
            $sorties = $sortieRepo->findByEtat($etat);
            dump($sorties);

            foreach ($sorties as $sortie){
                if ($sortie->getDatedebut() == $date){
                    $this->setEtatSortie();

                }

                if($sortie->getDatecloture() > $date){
                    $this->setEtatSortie();
                }
                $em->persist($sortie);
                $em->flush();
            }
            //mise à jour état en cours -> passée
            $etat=4;
            $sorties = $sorties = $sortieRepo->findByEtat($etat);
            foreach ($sorties as $sortie){
                if ($sortie->getDatedebut() > $date)
                    $sortie->setEtat(5);
                $em->persist($sortie);
                $em->flush();
            }



        }


}
