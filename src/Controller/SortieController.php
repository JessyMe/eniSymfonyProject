<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Inscription;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Form\VilleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('sortie/afficherSortie.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    /**
     * @Route ("", name="sortie_add")
     */
    public function add(EntityManagerInterface $em, Request $request)
    {
        //$user = $this->get('security.context')->getToken()->getUser();
        $user = $em->getRepository(User::class)->find($this->getUser());
        $etat = $this->getDoctrine()
            ->getRepository(Etat::class)
            ->find('1');

        $sortie = new Sortie();
        $sortie->setEtat($etat);
        $sortie->setCampus( $user->getCampus());

        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        dump($sortieForm);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            $sortie->setOrganisateur($user);
            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'Nouvelle sortie publiée');
            return $this->redirectToRoute("main_home");
        }

        return $this->render("sortie/add.html.twig", [
            'sortieForm'=>$sortieForm->createView(),

        ]);
    }

    /**
     * @Route ("/Sortie/AfficherSortie/{id}", name="afficherSortie", requirements={"id": "\d+"})
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
     */
    public function inscriptionSortie(EntityManagerInterface $em, $id)
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



}
