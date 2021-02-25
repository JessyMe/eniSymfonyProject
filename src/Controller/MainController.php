<?php

namespace App\Controller;

//use App\Entity\Etat;
use App\Entity\Inscription;
use App\Entity\User;
use App\Service\ListFormSortie;
use App\Entity\Campus;
use App\Entity\Sortie;
use App\Form\ListSortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function List(Request $request, PropertyAccessorInterface $pa)
    {
//----------- CHECK Type de device ----------------------------
    $headers = $request->headers->all();
    $head = $pa->getValue($headers, '[user-agent]');
    $userAgent = $pa->getValue($head, '[0]');
    dump($request);
    dump($headers);
    dump($head);
    dump($userAgent);

    $needle = 'Mobile';
    $mobileOrNot = strpos($userAgent, $needle);
    dump($mobileOrNot);

    //--------------- Desktop --------------------------------
        if ($mobileOrNot === false)
        {

            //Liste Campus pour menu déroulant
            $campusRepository = $this->getDoctrine()->getRepository(Campus::class);
            $campus = $campusRepository->findAll();
            //création du formulaire
            $ListFormSortie = new ListFormSortie();
            $sortieForm = $this->createForm(ListSortieType::class,$ListFormSortie);
            $sortieForm->handleRequest($request);

            //Liste des inscriptions pour affichage
            $InscriptionRepository = $this->getDoctrine()->getRepository(Inscription::class);
            $inscriptions = $InscriptionRepository->findAll();

            if($sortieForm->isSubmitted()){
                $userLog = $this->getUser();
                $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
                $sorties = $sortieRepository->findByFormFilter($ListFormSortie, $userLog);
                dump($sorties);
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
        //------------- Redirect to mobile -----------------
        }else if($mobileOrNot != false)
            {
                return $this->redirectToRoute('home_mobile');
            }
        }

    /**
     * @Route("/m", name="home_mobile")
     */
        public function  ListMobile ()
        {
            $userLog = $this->getUser()->getUsername();
            $userRepository = $this->getDoctrine()->getRepository(User::class);
            $user = $userRepository->findBy(['email'=>$userLog]);
            dump($user);

            //list des sorties pour le campus de l'user connecté
            $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
            $sortieCampusUser = $sortieRepository->findBy(['campus' => $user[0]->getCampus()]);
            dump($sortieCampusUser);

            return $this->render('Page/homeMobile.html.twig',
                ["user"=>$userLog,
                    "sorties"=>$sortieCampusUser
                    ]);





        }







//        public function setEtatSortie()
//        {
//            $em = $this->getDoctrine()->getManager();
//            //mise à jour état ouverte -> en cours ou cloturée
//            $date = new \DateTime();
//            $etat = 2;
//            $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
//            $sorties = $sortieRepo->findByEtat($etat);
//            dump($sorties);
//
//            foreach ($sorties as $sortie){
//                if ($sortie->getDatedebut() == $date){
//                    $this->setEtatSortie();
//
//                }
//
//                if($sortie->getDatecloture() > $date){
//                    $this->setEtatSortie();
//                }
//                $em->persist($sortie);
//                $em->flush();
//            }
//            //mise à jour état en cours -> passée
//            $etat=4;
//            $sorties = $sorties = $sortieRepo->findByEtat($etat);
//            foreach ($sorties as $sortie){
//                if ($sortie->getDatedebut() > $date)
//                    $sortie->setEtat(5);
//                $em->persist($sortie);
//                $em->flush();
//            }
//
//        }
}
