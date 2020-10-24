<?php

namespace App\Controller;

use App\Entity\Agriculteur;
use App\Entity\Client;
use App\Entity\Admin;
use App\Entity\Commune;
use App\Entity\Region;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\UserType;
use App\Form\ClientType;
use App\Repository\AdminRepository;
use App\Repository\AgriculteurRepository;
use App\Repository\CategorieRepository;
use App\Repository\ClientRepository;
use App\Repository\CommuneRepository;
use App\Repository\RegionRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Symfony\Component\Security\Core\Util\SecureRandom;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class SecurityController extends AbstractController
{
    /**
     * @Route("/Agr_registration1", name="agr_registration1")
     */
    public function Agr_registration1(CategorieRepository $categorieRepository,RegionRepository $regionRepository, CommuneRepository $communeRepository,VilleRepository $villeRepository, Request $request, EntityManagerInterface $em, SessionInterface $session)
    {
        $categories = $categorieRepository->findCategoryNotEmpty();
        
        $regions=$regionRepository->findAll();
        $commune = new Commune;
        $agriculteur = new Agriculteur;
        $form = $this->createFormBuilder()
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('cin', TextType::class)
            ->add('adresse', TextType::class)
            ->add('telephon', TextType::class)
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'libelle',
                
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'libelle',
            ])
            ->add('commune', EntityType::class, [
                'class' => Commune::class,
                'choice_label' => 'libelle',
                 
            ])
            ->getForm();
        if ($request->request->has('activeregion')) {
            $region = $request->request->get('region');
            $villes = $villeRepository->findBy(['region' => $region]);
             $communes=$communeRepository->findBy(['ville' => $villes[0]]);
            $form->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choices' => $villes,
                'choice_label' => 'libelle'
            ])->add('commune', EntityType::class, [
                'class' => Commune::class,
                'choices' => $communes,
                'choice_label' => 'libelle'
            ]);
        }
        if ($request->request->has('activeville')) {
            $ville = $request->request->get('ville');
            $communes = $communeRepository->findBy(['ville' => $ville]);
            $form->add('commune', EntityType::class, [
                'class' => Commune::class,
                'choices' => $communes,
                'choice_label' => 'libelle'
            ]);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
             
            $random = 'agr' . random_int(1, 100000000);
            $agriculteur->setNom($data['nom']);
            $agriculteur->setReference($random);
            $agriculteur->setPrenom($data['prenom']);
            $agriculteur->setCin($data['cin']);
            $agriculteur->setAdresse($data['adresse']);
            $agriculteur->setTele($data['telephon']);

            $agriculteur->setCommune($data['commune']);
            $em->persist($data['commune']);
            $em->persist($agriculteur);
            $em->detach($agriculteur);
            $session->set('agriculteur', $agriculteur);
            return $this->redirectToRoute('agr_registration2');
        }

        return $this->render('security/Arg_registration.html.twig', [
            'form' => $form->createView(),'categories'=>$categories
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
            'form' => $form->createView(),'categories'=>$categories
        ]);
    }


    /**
     * @Route("/login", name="login")
     */
    public function login(CategorieRepository $categorieRepository,Request $request, AuthenticationUtils $authenticationUtils)
    {
        $categories = $categorieRepository->findCategoryNotEmpty();
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/login.html.twig', [
            'error' => $error, 'categories'=>$categories
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
public function Client_registration1(CategorieRepository $categorieRepository,Request $request, EntityManagerInterface $em, SessionInterface $session, MailerInterface $mailer){ 
    $categories = $categorieRepository->findCategoryNotEmpty();
    $client= new Client;
    
    $form = $this->createForm(ClientType::class, $client);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $random = 'cli' . random_int(1, 100000000);     
        $client->setReference($random);
        $em->persist($client);
        $em->detach($client);
        $session->set('client', $client);
        return $this->redirectToRoute('client_registration2');
    }

        $client = new Client;

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $random = 'agr' . random_int(1, 100000000);
            $client->setReference($random);
            $em->persist($client);
            $em->flush();
            $em->detach($client);
            $session->set('client', $client);
            return $this->redirectToRoute('client_registration2');
        }

        return $this->render('security/client_registration1.html.twig', [
            'form' => $form->createView(),'categories'=>$categories
        ]);
    }


    /**
     * @Route("/client_registration2", name="client_registration2")
     */
    public function Client_registration2(CategorieRepository $categorieRepository,Request $request, EntityManagerInterface $em, SessionInterface $session, UserPasswordEncoderInterface $encoder)
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
                $user->setClient($client);
                $hash = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hash);
                $user->setRoles(['ROLE_CLIENT']);
                $em->persist($client);
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('login');
            }
           
        }
        return $this->render('security/User_registration.html.twig', [
            'form' => $form->createView(),'categories'=>$categories
        ]);
    }


    /**
     * @Route("/emailValidation", name="emailValidation")
     */

    public function Validation(CategorieRepository $categorieRepository,Request $request,EntityManagerInterface $em, SessionInterface $session, UserPasswordEncoderInterface $encoder, MailerInterface $mailer)
    { 
        $categories = $categorieRepository->findCategoryNotEmpty(); 
        $agriculteur = $session->get('agriculteur');
        $agriculteur = $em->merge($agriculteur);
        $user = $session->get('user');
        $user = $em->merge($user);
        $user->setAgriculteur($agriculteur);  
        $password= $session->get('password');
        $error= '';

        $form = $this->createFormbuilder()
        ->add('password', PasswordType::class)
        ->getForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $codeEntre= $data['password'];
            if ($codeEntre!=$password) {
                $error= 'Code incorecte ';
                return $this->render('security/emailValidation.html.twig', [
                  'form' => $form->createView(), 'error'=>$error, 'categories'=>$categories
              ]);
             }
        
        $hash = $encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($hash);
        $user->setRoles(['ROLE_AGRICULTEUR']);
        $em->persist($agriculteur);
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('login');
    }
     /////////////////  SEND EMAIL/ RESEND EMAIL ////////////////////////
     $password = random_int(1, 1000000) ;
     $session->set('password',$password);
     $email = (new TemplatedEmail())
     ->from('outhounazakaria1@gmail.com')
     ->to($user->getEmail())
     ->subject('Confirmation d\'email: GREENSTORE. ')
     // path of the Twig template to render
    ->htmlTemplate('security/emails/validation.html.twig')

     // pass variables (name => value) to the template
     ->context([
         'username' => $agriculteur->getNom(),
         'password' => $password ,
         ]);
     $mailer->send($email);
    ////////////////////////////////////////////////////////////////////
    return $this->render('security/emailValidation.html.twig', [
        'form' => $form->createView() ,'categories'=>$categories,'error'=>$error
    ]);

}   
 
    /**
     * @Route("/forget_password", name="forget_password")
     */

    public function forgetPassword(AdminRepository $adminRepository,ClientRepository $clientRepository,AgriculteurRepository $agriculteurRepository,UserRepository $userRepository,CategorieRepository $categorieRepository,Request $request,EntityManagerInterface $em, SessionInterface $session,  MailerInterface $mailer)
    { 
        $categories = $categorieRepository->findCategoryNotEmpty(); 
        $user= new User;
        $error= '';
        $form = $this->createFormbuilder()
        ->add('email', EmailType::class)
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data= $form->getData();
            $emailEntre=$data["email"];
            $user=$userRepository->findOneBy(['email' => $emailEntre]);
            if (!$user) {
                $error= 'Cette email n\'éxiste pas, essayer avec une autre adresse email';
                return $this->render('security/forget_password.html.twig', [
                    'form' => $form->createView() ,'categories'=>$categories,'error'=>$error
                ]);
            }
            else {
        $session->set('email', $emailEntre)  ;  
        ////////////// TROUVER L'UTILISATEUR DU COMPTE /////////////////
        if ($user->getAgriculteur()) {
            $idUtilisateur= $user->getAgriculteur();
            $utilisateur= new Agriculteur();
            $type = 'Agriculteur';
            $utilisateur= $agriculteurRepository->findOneBy(['id'=>$idUtilisateur]);       
        }
        elseif ($user->getClient()) {
            $idUtilisateur= $user->getClient();
            $utilisateur= new Client();
            $type = 'Client';
            $utilisateur= $clientRepository->findOneBy(['id'=>$idUtilisateur]);
        }
        else {
            $idUtilisateur= $user->getAdmin();
            $utilisateur= new Admin();
            $type = 'Admin';
            $utilisateur= $adminRepository->findOneBy(['id'=>$idUtilisateur]);
        }
         ///password//////////////  SEND EMAIL/ RESEND EMAIL ////////////////////////
        $code ='CD' . random_int(1, 1000000) ;
        $session->set('code',$code);
        $email = (new TemplatedEmail())
        ->from('outhounazakaria1@gmail.com')
        ->to($user->getEmail())
        ->subject(' GREENSTORE  ')
        // path of the Twig template to render
        ->htmlTemplate('security/emails/forget_password.html.twig')

        // pass variables (name => value) to the template
        ->context([
             'username' => $utilisateur->getNom(),
            'code' => $code ,
            'type'=>$type,
            ]);
        $mailer->send($email);
        ////////////////////////////////////////////////////////////////////
        return $this->redirectToRoute('confirmCode');
            }

        }

        return $this->render('security/forget_password.html.twig', [
            'form' => $form->createView() ,'categories'=>$categories,'error'=>$error
        ]);   
    }

     
    /**
     * @Route("/confirmCode", name="confirmCode")
     */
    public function confirmCode(UserRepository $userRepository,CategorieRepository $categorieRepository,Request $request,EntityManagerInterface $em, SessionInterface $session)
    {
        $categories = $categorieRepository->findCategoryNotEmpty(); 
        $email= $session->get('email');
        $codeEnvoye= $session->get('code');
        $error= '';
        $form = $this->createFormbuilder()
        ->add('password', PasswordType::class)
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data= $form->getData();
            $codeEntre=$data['password'];
            if ($codeEntre!= $codeEnvoye) {
                $error='Code incorecte';
                return $this->render('security/confirm_code.html.twig', [
                    'form' => $form->createView() ,'categories'=>$categories,'error'=>$error
                ]); 
            }
            else {

               return $this->redirectToRoute('resetPassWord');
            }
        }
        return $this->render('security/confirm_code.html.twig', [
            'form' => $form->createView() ,'categories'=>$categories,'error'=>$error
        ]); 
    }

     
    /**
     * @Route("/resetPassWord", name="resetPassWord")
     */
    public function resetPassWord(UserRepository $userRepository,UserPasswordEncoderInterface $encoder,CategorieRepository $categorieRepository,Request $request,EntityManagerInterface $em, SessionInterface $session)
    {
        $categories = $categorieRepository->findCategoryNotEmpty(); 
        $error= '';
        $form = $this->createFormbuilder()
        ->add('password', PasswordType::class)
        ->add('confirm_password', PasswordType::class)
        ->getForm();
        $form->handleRequest($request);
        $email=$session->get("email");
        if ($form->isSubmitted() && $form->isValid()) {
            $data= $form->getData();
            $password= $data["password"];
            $confirm_password= $data["confirm_password"];
            if (strlen($password)<8) {
                $error='Le mot de passe doit contenir au moin 8 caractéres';
                return $this->render('security/reset_password.html.twig', [
                    'form' => $form->createView() ,'categories'=>$categories,'error'=>$error, 'email'=>$email
                ]);  
            }
          elseif ($password!=$confirm_password) {
            $error='Le mot de passe de confirmation n\'est pas correct ';
            return $this->render('security/reset_password.html.twig', [
                'form' => $form->createView() ,'categories'=>$categories,'error'=>$error, 'email'=>$email
            ]);  
          }
          else {
            $user= new User();
            $user=$userRepository->findOneBy(['email'=>$email]);
            $hash = $encoder->encodePassword($user, $password);
            $user->setPassword($hash);
            return $this->redirectToRoute('login');
            
          }
        }
        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView() ,'categories'=>$categories,'error'=>$error , 'email'=> $email
        ]); 
    }
}

