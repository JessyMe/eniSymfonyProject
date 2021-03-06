<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Inscription;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\NewLieuType;
use App\Form\SortieType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{


    /**
     * @Route ("", name="sortie_add")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function add(EntityManagerInterface $em, Request $request)
    {
        $sortie = $this->setNewSortie($em);

        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'Nouvelle sortie publiée');
            return $this->redirectToRoute("main_home");
        }

        return $this->render("sortie/add.html.twig", [
            "sortie"=>$sortie,
            'sortieForm'=>$sortieForm->createView(),

        ]);
    }
    /**
     * @Route ("nouveauLieu", name="sortie_addLieu")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addLieu (EntityManagerInterface $em, Request $request)
    {

        $sortie = $this->setNewSortie($em);
        $sortieForm = $this->createForm(NewLieuType::class, $sortie);
        $sortieForm->handleRequest($request);


        if ($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'Nouvelle sortie publiée');
            return $this->redirectToRoute("main_home");
        }
        return $this->render("sortie/add.html.twig", [
            "sortie"=>$sortie,
            'sortieForm'=>$sortieForm->createView(),
        ]);
    }


    /**
     * @param EntityManagerInterface $em
     * @return RedirectResponse|Response
     */
 public function setNewSortie (EntityManagerInterface $em)
 {
     $user = $em->getRepository(User::class)->find($this->getUser());
     $sortie = new Sortie();
     $etat = $this->getDoctrine()
         ->getRepository(Etat::class)
         ->find('1');
     $sortie->setCampus( $user->getCampus());
     $sortie->setOrganisateur($user);
     $sortie->setEtat($etat);

     return $sortie;
 }


    /**
     * @Route ("/detail/{id}", name="afficherSortie", requirements={"id": "\d+"})
     */
    public function AfficherSortie(EntityManagerInterface $em,$id)
    {
        $sortieRepository = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepository->find($id);

        $inscriptionRepository = $this->getDoctrine()->getRepository(Inscription::class);
        $inscription = $inscriptionRepository->findBy(["sortie"=>$id]);

        dump($inscription);


        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->findAll();

        dump($user);
        return $this->render('sortie/afficherSortie.html.twig',["sortie"=>$sortie,
            "inscriptions"=>$inscription, "id"=>$id, "users"=>$user]);
    }

    /**
     * @Route ("/delete/{id}", name="sortie_delete", requirements={"id": "\d+"})
     */
    public function delete (EntityManagerInterface $em, $id)
    {

        $sortieRepo =$this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        $em->remove($sortie);
        $em->flush();
        $this->addFlash('success', "Sortie annulée");
        return $this->redirectToRoute("main_home");
    }

    /**
     * @Route ("/modifier/{id}", name="sortie_update", requirements={"id": "\d+"})
     */
     public function update (EntityManagerInterface $em, $id, Request $request) : Response
    {

    $sortie = $em->getRepository(Sortie::class)->find($id);
    $sortieForm = $this->createForm(SortieType::class, $sortie);
    $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            if ($sortieForm->get('saveAndAdd')->isClicked()) {
                $etat = $this->getDoctrine()
                    ->getRepository(Etat::class)
                    ->find('2');
            } else {
                $etat = $this->getDoctrine()
                    ->getRepository(Etat::class)
                    ->find('1');
            }
            $sortie->setEtat($etat);

            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'Sortie modifiée');
            return $this->redirectToRoute("main_home");
        }
        return $this->render('sortie/add.html.twig', [
            'sortie' => $sortie,
            'sortieForm_title' => "Modifer une sortie",
            'sortieForm' => $sortieForm->createView(),
            ]);
    }

    /**
     * @Route ("/inscription/{id}", name="sortie_inscription", requirements={"id": "\d+"})
     * @param EntityManagerInterface $em
     * @param $id
     * @return RedirectResponse
     */
    public function inscriptionSortie(EntityManagerInterface $em, $id): RedirectResponse
    {
        $user = $em->getRepository(User::class)->find($this->getUser());
        $sortie = $em->getRepository(Sortie::class)->find($id);


        $inscription = new Inscription();
        $inscription->setDateInscription(new DateTime());
        $inscription->setParticipant($user);
        $inscription->setSortie($sortie);

        $em->persist($inscription);
        $em->flush();

        $this->addFlash('success', "Inscription à la sortie réussie");
        return $this->redirectToRoute("main_home");

    }

    /**
     * @Route ("/desister/{id}", name="sortie_desister", requirements={"id": "\d+"}, methods={"GET"})
     * @param EntityManagerInterface $em
     * @param $id
     * @return RedirectResponse
     */
    public function desisterSortie(EntityManagerInterface $em, $id): RedirectResponse
    {
        $sortie = $em->getRepository(Sortie::class)->find($id);
        $user = $em->getRepository(User::class)->find($this->getUser());
        $inscription = $em->getRepository(Inscription::class)->findOneByParticipantAndSortie($sortie, $user);

        foreach ($inscription as $participant) {
            $idParticipant = $participant;
        }

        $em->remove($idParticipant);
        $em->flush();

        $this->addFlash('success', "Désistement à la sortie enregistré");
        return $this->redirectToRoute("main_home");

    }
    public function index(): Response
    {
        return $this->render('sortie/afficherSortie.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }


}
