<?php

namespace App\Controller\Farmer;

use App\Entity\Agriculteur;
use App\Entity\Commune;
use App\Entity\Region;
use App\Entity\User;
use App\Entity\Ville;
use App\Repository\AgriculteurRepository;
use App\Repository\CommuneRepository;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/agriculteur/profile" )
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="farmer_profile")
     */
    public function index(AgriculteurRepository $agriculteurRepository)
    {
        $user = $this->getUser();
        $agriculteur = $agriculteurRepository->findOneBy(array('id' => $user->getAgriculteur()->getId()));
        return $this->render('farmer/profile/index.html.twig', [
            'controller_name' => 'profile', 'user' => $user, 'agriculteur' => $agriculteur
        ]);
    }

    /**
     * @Route("/parametres", name="setting")
     */
    public function setting()
    {
        return $this->render('farmer/profile/setting.html.twig', [
              'controller_name'=>'profile | parametres '
        ]);
    }
    /**
     * @Route("/parametres/securite", name="securite")
     */
    public function securite(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $user = $this->getUser();
        $form = $this->createFormBuilder()
            ->add('MotDePasseActuel', PasswordType::class)
            ->add('NouvaeuMotDePasse', PasswordType::class)
            ->add('RetapezLeNouveaauMotDePasse', PasswordType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $u = new User();
            if ($encoder->isPasswordValid($user, $data['MotDePasseActuel'])) {
                if (strlen($data['NouvaeuMotDePasse']) >= 8) {
                    if ($data['NouvaeuMotDePasse'] == $data['RetapezLeNouveaauMotDePasse']) {
                        $user->setPassword($encoder->encodePassword($user, $data['NouvaeuMotDePasse']));
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($user);
                        $em->flush();
                        return $this->redirectToRoute('setting');
                    } else {
                        return $this->render('farmer/profile/securite.html.twig', [
                            'controller_name' => 'profile | securite ', 'form' => $form->createView(), "message" => "vouz n'avez pas tapé le meme mot de passe"
                        ]);
                    }
                } else {
                    return $this->render('farmer/profile/securite.html.twig', [
                        'controller_name' => 'profile | securite', 'form' => $form->createView(), "message" => "votre mot de passe doit faire minimum 8 caractères"
                    ]);
                }
            } else {
                return $this->render('farmer/profile/securite.html.twig', [
                    'controller_name' => 'profile | securite', 'form' => $form->createView(), "message" => "Mot de passe actuel incorrect"
                ]);
            }
        }
        return $this->render('farmer/profile/securite.html.twig', [
            'controller_name' => 'profile | securite', 'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/parametres/infos", name="infos")
     */
    public function infos(CommuneRepository $communeRepository, Request $request, AgriculteurRepository $agriculteurRepository, VilleRepository $villeRepository)
    {
        $agriculteur = new Agriculteur;
        $user = $this->getUser();
        $agriculteur = $agriculteurRepository->findOneBy(array('id' => $user->getAgriculteur()->getId()));
        $form = $this->createFormBuilder()
            ->add('nom', TextType::class, [
                'data' => $agriculteur->getNom()
            ])
            ->add('prenom', TextType::class, [
                'data' => $agriculteur->getPrenom()
            ])
            ->add('cin', TextType::class, [
                'data' => $agriculteur->getCin()
            ])
            ->add('adresse', TextType::class, [
                'data' => $agriculteur->getAdresse()
            ])
            ->add('telephon', TelType::class, [
                'data' => $agriculteur->getTele()
            ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'libelle',
                'data' => $agriculteur->getCommune()->getVille()->getRegion()
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'libelle',
                'data' => $agriculteur->getCommune()->getVille(),
            ])
            ->add('commune', EntityType::class, [
                'class' => Commune::class,
                'choice_label' => 'libelle',
                'data' => $agriculteur->getCommune(),

            ])

            ->getForm();
        if ($request->request->has('activeregion')) {
            $region = $request->request->get('region');
            $villes = $villeRepository->findBy(['region' => $region]);
            $communes = $communeRepository->findBy(['ville' => $villes[0]]);
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
            $agriculteur->setNom($data['nom']);
            $agriculteur->setReference($agriculteur->getReference());
            $agriculteur->setPrenom($data['prenom']);
            $agriculteur->setCin($data['cin']);
            $agriculteur->setAdresse($data['adresse']);
            $agriculteur->setTele($data['telephon']);
            $agriculteur->setCommune($data['commune']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($agriculteur);
            $em->flush();
            return $this->redirectToRoute('setting');
        }
        return $this->render('farmer/profile/personnel.html.twig', [
            'controller_name' => 'parametres |info ', 'form' => $form->createView()
        ]);
    }
}
