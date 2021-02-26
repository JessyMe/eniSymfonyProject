<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Form\RoleType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/registeruser", name="admin_registeruser")
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $registerForm = $this->createForm(RegisterType::class, $user);
        $user->setDateCreated(new DateTime());
        $user->setRoles(['ROLE_USER']);


        $registerForm->handleRequest($request);
        if ($registerForm->isSubmitted() && $registerForm->isValid())
        {
            //hash password
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Inscription réalisée avec succés !');
            return $this->redirectToRoute('main_home');
        }

        return $this->render('user/register.html.twig', [
            'user' => $user,
            "registerForm" => $registerForm->createView(),

        ]);
    }

    /**
     * @Route("/userroles", name="admin_userroles")
     */
    public function listUserRoles(Request $request, EntityManagerInterface $em)
    {
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/gestionUsers.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/roleuser/{id}", name="admin_roleuser", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function changeRoleUser(EntityManagerInterface $em, $id): RedirectResponse
    {
        $user = $em->getRepository(User::class)->find($id);

        $user->setRoles(['ROLE_USER']);

        $em->flush();

        return $this->redirectToRoute('admin_userroles');
    }

    /**
     * @Route("/roleadmin/{id}", name="admin_roleadmin", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function changeRoleAdmin(EntityManagerInterface $em, $id): RedirectResponse
    {
        $user = $em->getRepository(User::class)->find($id);

        $user->setRoles(['ROLE_ADMIN']);

        $em->flush();

        return $this->redirectToRoute('admin_userroles');
    }

    /**
     * @Route("/deleteuser/{id}", name="admin_deleteuser", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function deleteUser(EntityManagerInterface $em, $id): RedirectResponse
    {
        $user = $em->getRepository(User::class)->find($id);


        $em->remove($user);
        $em->flush();


        return $this->redirectToRoute('admin_userroles');
    }
}