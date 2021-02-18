<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $registerForm = $this->createForm(RegisterType::class, $user);
        $user->setDateCreated(new \DateTime());
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
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/register.html.twig', [
            "registerForm" => $registerForm->createView(),

        ]);
    }

//    /**
//     * @Route("/login", name="login")
//     */
//    public function login(): Response
//    {
//        return $this->render('security/login.html.twig');
//    }
//
//    /**
//     * @Route("/logout", name="logout")
//     */
//    public function logout(){}
}
