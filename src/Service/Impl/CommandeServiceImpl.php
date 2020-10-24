<?php

namespace App\Service\Impl;

use App\Entity\Livraison;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Entity\TypeLivraison;
use App\Repository\ClientRepository;
use App\Repository\TypeLivraisonRepository;
use App\Service\CommandeService;
use App\Service\FactureService;
use App\Service\PanierService;
use DateInterval;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CommandeServiceImpl extends AbstractController  implements CommandeService
{
    private $agriculteurRepository;
    private $panierService;
    private $factureService;
    private $clientRepository;
    private $typeLivraisonRepository;

    public function __construct(TypeLivraisonRepository $typeLivraisonRepository, PanierService $panierService, ClientRepository $clientRepository, FactureService $factureService)
    {
        $this->panierService = $panierService;
        $this->clientRepository = $clientRepository;
        $this->factureService = $factureService;
        $this->typeLivraisonRepository = $typeLivraisonRepository;
    }

    public function makeReference(): string
    {
        $date = date_format(new \DateTime, 'my');
        return   strval(random_int(0, 1000)) . '/' . $date;
    }

    public function save($request)
    {
        $panier = $this->panierService->getFullPanier();

        $commande = new Commande;
        $livraison = new Livraison;
        $ligneCommande = new LigneCommande();
        $em = $this->getDoctrine()->getManager();
        foreach ($panier as $item) {
            $ligneCommande = new LigneCommande();
            $ligneCommande->setProduit($item['produit']);
            $ligneCommande->setQteCommande($item['qte']);

            // save ligne de commande!!!

            // total avec TVA et reduction
            $total = $this->panierService->getTotal() + $this->panierService->getTotal() * 0.2;
            $commande->setTotal($total);

            $client = $this->clientRepository->findOneBy(array('id' => $this->getUser()->getClient()->getId()));
            $commande->setClient($client);

            $commande->setReference($this->makeReference());
            $ligneCommande->setCommande($commande);
            $commande->addLigneCommande($ligneCommande);
            $em->persist($ligneCommande);
        }
        if ($request->request->has('add_commande')) {
            $data = $request->request->all();
            dd($data);
            $isCurrentAdress = $request->get("isCurrentAdress");
            // $isCurrentAdress = $data['isCurrentAdress'];
            $otherAdress = $data['otherAdress'];
            $typeLivraisonId = $data['shipping'];
            $typeLivraison = new TypeLivraison;
            $typeLivraison = $this->typeLivraisonRepository->find($typeLivraisonId);

            $date = new \DateTime;
            $date->add(new DateInterval('P' . $typeLivraison->getDuree() / 24 . 'D'));
           
            if (!$isCurrentAdress) {
                $livraison->setCommande($commande);
                $livraison->setAdresse($commande->getClient()->getAdresse());
                $livraison->setTypeLivraison($typeLivraison);
                $livraison->setDateLivraison($date);
                $livraison->setLivree(false);
            } else {
                $livraison->setCommande($commande);
                $livraison->setAdresse($otherAdress);
                $livraison->setTypeLivraison($typeLivraison);
                $livraison->setDateLivraison($date);
                $livraison->setLivree(false);
            }
            $em->persist($livraison);
        }


        $em->persist($commande);
        $em->flush();
        $data = $request->request->all();
        $this->factureService->save($commande, $data);
    }
}
