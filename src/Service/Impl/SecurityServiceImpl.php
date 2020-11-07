<?php

namespace App\Service\Impl;

use App\Entity\Admin;
use App\Entity\Agriculteur;
use App\Entity\Client;
use App\Entity\Commune;
use App\Entity\Region;
use App\Entity\User;
use App\Entity\Ville;
use App\Repository\AdminRepository;
use App\Repository\AgriculteurRepository;
use App\Repository\ClientRepository;
use App\Repository\CommuneRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use App\Service\SecurityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class SecurityServiceImpl extends AbstractController implements SecurityService
{

    private $communeRepository;
    private $villeRepository;
    private $agriculteurRepository;
    private $clientRepository;
    private $adminRepository;
    private $userRepository;
    private $em;
    private $encoder;
    private $mailer;

    public function __construct(UserRepository $userRepository, AgriculteurRepository $agriculteurRepository, ClientRepository $clientRepository, AdminRepository $adminRepository, EntityManagerInterface $em, CommuneRepository $communeRepository, VilleRepository $villeRepository, UserPasswordEncoderInterface $encoder, MailerInterface $mailer)
    {

        $this->communeRepository = $communeRepository;
        $this->villeRepository = $villeRepository;
        $this->em = $em;
        $this->encoder = $encoder;
        $this->mailer = $mailer;
        $this->agriculteurRepository = $agriculteurRepository;
        $this->clientRepository = $clientRepository;
        $this->adminRepository = $adminRepository;
        $this->userRepository = $userRepository;
    }

    public function agr_form1($request)
    {

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
            $villes = $this->villeRepository->findBy(['region' => $region]);
            $communes = $this->communeRepository->findBy(['ville' => $villes[0]]);
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
            $communes = $this->communeRepository->findBy(['ville' => $ville]);
            $form->add('commune', EntityType::class, [
                'class' => Commune::class,
                'choices' => $communes,
                'choice_label' => 'libelle'
            ]);
        }
        $form->handleRequest($request);
        return $form;
    }

    public function persist_agr($data)
    {
        $agriculteur = new Agriculteur;
        $random = 'agr' . random_int(1, 100000000);
        $agriculteur->setNom($data['nom']);
        $agriculteur->setReference($random);
        $agriculteur->setPrenom($data['prenom']);
        $agriculteur->setCin($data['cin']);
        $agriculteur->setAdresse($data['adresse']);
        $agriculteur->setTele($data['telephon']);

        $agriculteur->setCommune($data['commune']);
        $this->em->persist($data['commune']);
        $this->em->persist($agriculteur);
        $this->em->detach($agriculteur);

        return $agriculteur;
    }

    public function save_cli($user, $client)
    {
        $user->setClient($client);
        $hash = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($hash);
        $user->setRoles(['ROLE_CLIENT']);
        $this->em->persist($client);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function send_validation_email($user, $agriculteur)
    {
        $password = random_int(1, 1000000);
        $email = (new TemplatedEmail())
            ->from('outhounazakaria1@gmail.com')
            ->to($user->getEmail())
            ->subject('Confirmation d\'email: GREENSTORE. ')
            // path of the Twig template to render
            ->htmlTemplate('security/emails/validation.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'username' => $agriculteur->getNom(),
                'password' => $password,
            ]);
        $this->mailer->send($email);

        return $password;
    }

    public function persist_cli($client)
    {
        $random = 'cli' . random_int(1, 100000000);
        $client->setReference($random);
        $this->em->persist($client);
        $this->em->detach($client);

        return $client;
    }

    public function seve_agr($user, $agriculteur)
    {
        $hash = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($hash);
        $user->setRoles(['ROLE_AGRICULTEUR']);
        $this->em->persist($agriculteur);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function account_user($user)
    {

        if ($user->getAgriculteur()) {
            $idUtilisateur = $user->getAgriculteur();
            $utilisateur = new Agriculteur();
            $type = 'Agriculteur';
            $utilisateur = $this->agriculteurRepository->findOneBy(['id' => $idUtilisateur]);
        } elseif ($user->getClient()) {
            $idUtilisateur = $user->getClient();
            $utilisateur = new Client();
            $type = 'Client';
            $utilisateur = $this->clientRepository->findOneBy(['id' => $idUtilisateur]);
        } else {
            $idUtilisateur = $user->getAdmin();
            $utilisateur = new Admin();
            $type = 'Admin';
            $utilisateur = $this->adminRepository->findOneBy(['id' => $idUtilisateur]);
        }

        return  array('utilisateur' => $utilisateur, 'type' => $type);
    }

    public function send_forget_password_email($utilisateur, $type, $email)
    {

        $code = 'CD' . random_int(1, 1000000);
        $email = (new TemplatedEmail())
            ->from('outhounazakaria1@gmail.com')
            ->to($email)
            ->subject(' GREENSTORE  ')
            // path of the Twig template to render
            ->htmlTemplate('security/emails/forget_password.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'username' => $utilisateur->getNom(),
                'code' => $code,
                'type' => $type,
            ]);
        $this->mailer->send($email);

        return $code;
    }

    public function reset_password($email, $password)
    {
        $user = new User();
        $user = $this->userRepository->findOneBy(['email' => $email]);
        $hash = $this->encoder->encodePassword($user, $password);
        $user->setPassword($hash);
    }
}
