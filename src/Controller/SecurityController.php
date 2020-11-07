<?php

namespace App\Controller;

use App\Entity\Agriculteur;
use App\Entity\Client;
use App\Entity\User;
use App\Form\UserType;
use App\Form\ClientType;
use App\Repository\CategorieRepository;
use App\Repository\UserRepository;
use App\Service\SecurityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\MailerInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/Agr_registration1", name="agr_registration1")
     */
    public function Agr_registration1(SecurityService $securityService, CategorieRepository $categorieRepository,  Request $request, SessionInterface $session)
    {
        $categories = $categorieRepository->findCategoryNotEmpty();
        $agriculteur = new Agriculteur;
        $form = $securityService->agr_form1($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $agriculteur = $securityService->persist_agr($data);
            $session->set('agriculteur', $agriculteur);
            return $this->redirectToRoute('agr_registration2');
        }
        return $this->render('security/Arg_registration.html.twig', [
            'form' => $form->createView(), 'categories' => $categories
        ]);
    }

    /**
     * @Route("/Agr_registration2", name="agr_registration2")
     */
    public function Agr_registration2(CategorieRepository $categorieRepository, Request $request, EntityManagerInterface $em, SessionInterface $session, MailerInterface $mailer)
    {
        $categories = $categorieRepository->findCategoryNotEmpty();
        $user = new User;

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->detach($user);
            $session->set('user', $user);
            return $this->redirectToRoute('emailValidation');
        }
        return $this->render('security/User_registration.html.twig', [
            'form' => $form->createView(), 'categories' => $categories
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(CategorieRepository $categorieRepository,  AuthenticationUtils $authenticationUtils)
    {
        $categories = $categorieRepository->findCategoryNotEmpty();
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/login.html.twig', [
            'error' => $error, 'categories' => $categories
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/client_registration1", name="client_registration1")
     */
    public function Client_registration1(SecurityService $securityService, CategorieRepository $categorieRepository, Request $request,  SessionInterface $session)
    {
        $categories = $categorieRepository->findAll();
        $client = new Client;

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $client = $securityService->persist_cli($client);
            $session->set('client', $client);
            return $this->redirectToRoute('client_registration2');
        }
        return $this->render('security/client_registration1.html.twig', [
            'form' => $form->createView(), 'categories' => $categories
        ]);
    }

    /**
     * @Route("/client_registration2", name="client_registration2")
     */
    public function Client_registration2(SecurityService $securityService, CategorieRepository $categorieRepository, Request $request, EntityManagerInterface $em, SessionInterface $session)
    {
        $categories = $categorieRepository->findCategoryNotEmpty();

        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $client = new Client;
            $client = $session->get('client');
            if ($client) {
                $client = $em->merge($client);
                $securityService->save_cli($user, $client);
                return $this->redirectToRoute('login');
            }
        }
        return $this->render('security/User_registration.html.twig', [
            'form' => $form->createView(), 'categories' => $categories
        ]);
    }

    /**
     * @Route("/emailValidation", name="emailValidation")
     */

    public function Validation(SecurityService $securityService, CategorieRepository $categorieRepository, Request $request, EntityManagerInterface $em, SessionInterface $session)
    {
        $categories = $categorieRepository->findCategoryNotEmpty();
        $agriculteur = $session->get('agriculteur');
        $agriculteur = $em->merge($agriculteur);
        $user = $session->get('user');
        $user = $em->merge($user);
        $user->setAgriculteur($agriculteur);
        $password = $session->get('password');
        $error = '';

        $form = $this->createFormbuilder()
            ->add('password', PasswordType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $codeEntre = $data['password'];
            if ($codeEntre != $password) {
                $error = 'Code incorecte ';
                return $this->render('security/emailValidation.html.twig', [
                    'form' => $form->createView(), 'error' => $error, 'categories' => $categories
                ]);
            }

            $securityService->seve_agr($user, $agriculteur);
            return $this->redirectToRoute('login');
        }
        /////////////////  SEND EMAIL/ RESEND EMAIL ////////////////////////
        $password = $securityService->send_validation_email($user, $agriculteur);
        $session->set('password', $password);
        ////////////////////////////////////////////////////////////////////
        return $this->render('security/emailValidation.html.twig', [
            'form' => $form->createView(), 'categories' => $categories, 'error' => $error
        ]);
    }

    /**
     * @Route("/forget_password", name="forget_password")
     */

    public function forgetPassword(SecurityService $securityService, UserRepository $userRepository, CategorieRepository $categorieRepository, Request $request, EntityManagerInterface $em, SessionInterface $session,  MailerInterface $mailer)
    {
        $categories = $categorieRepository->findCategoryNotEmpty();
        $user = new User;
        $error = '';
        $form = $this->createFormbuilder()
            ->add('email', EmailType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $emailEntre = $data["email"];
            $user = $userRepository->findOneBy(['email' => $emailEntre]);
            if (!$user) {
                $error = 'Cette email n\'éxiste pas, essayer avec une autre adresse email';
                return $this->render('security/forget_password.html.twig', [
                    'form' => $form->createView(), 'categories' => $categories, 'error' => $error
                ]);
            } else {
                $session->set('email', $emailEntre);
                $email = $user->getEmail();
                ////////////// TROUVER L'UTILISATEUR DU COMPTE /////////////////
                $acountUser = $securityService->account_user($user);
                $utilisateur = $acountUser["utilisateur"];
                $type = $acountUser["type"];
                ///password//////////////  SEND EMAIL/ RESEND EMAIL ////////////////////////
                $code = $securityService->send_forget_password_email($utilisateur, $type, $email);
                $session->set('code', $code);
                ////////////////////////////////////////////////////////////////////
                return $this->redirectToRoute('confirmCode');
            }
        }

        return $this->render('security/forget_password.html.twig', [
            'form' => $form->createView(), 'categories' => $categories, 'error' => $error
        ]);
    }

    /**
     * @Route("/confirmCode", name="confirmCode")
     */
    public function confirmCode(CategorieRepository $categorieRepository, Request $request, SessionInterface $session)
    {
        $categories = $categorieRepository->findCategoryNotEmpty();
        $email = $session->get('email');
        $codeEnvoye = $session->get('code');
        $error = '';
        $form = $this->createFormbuilder()
            ->add('password', PasswordType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $codeEntre = $data['password'];
            if ($codeEntre != $codeEnvoye) {
                $error = 'Code incorecte';
                return $this->render('security/confirm_code.html.twig', [
                    'form' => $form->createView(), 'categories' => $categories, 'error' => $error
                ]);
            } else {

                return $this->redirectToRoute('resetPassWord');
            }
        }
        return $this->render('security/confirm_code.html.twig', [
            'form' => $form->createView(), 'categories' => $categories, 'error' => $error
        ]);
    }

    /**
     * @Route("/resetPassWord", name="resetPassWord")
     */
    public function resetPassWord(SecurityService $securityService, CategorieRepository $categorieRepository, Request $request, SessionInterface $session)
    {
        $categories = $categorieRepository->findCategoryNotEmpty();
        $error = '';
        $form = $this->createFormbuilder()
            ->add('password', PasswordType::class)
            ->add('confirm_password', PasswordType::class)
            ->getForm();
        $form->handleRequest($request);
        $email = $session->get("email");
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $password = $data["password"];
            $confirm_password = $data["confirm_password"];
            if (strlen($password) < 8) {
                $error = 'Le mot de passe doit contenir au moin 8 caractéres';
                return $this->render('security/reset_password.html.twig', [
                    'form' => $form->createView(), 'categories' => $categories, 'error' => $error, 'email' => $email
                ]);
            } elseif ($password != $confirm_password) {
                $error = 'Le mot de passe de confirmation n\'est pas correct ';
                return $this->render('security/reset_password.html.twig', [
                    'form' => $form->createView(), 'categories' => $categories, 'error' => $error, 'email' => $email
                ]);
            } else {
                $securityService->reset_password($email, $password);
                return $this->redirectToRoute('login');
            }
        }
        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView(), 'categories' => $categories, 'error' => $error, 'email' => $email
        ]);
    }
}
