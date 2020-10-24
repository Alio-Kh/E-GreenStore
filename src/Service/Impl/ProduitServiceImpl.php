<?php

namespace App\Service\Impl;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\Promotion;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use App\Repository\PromotionRepository;
use App\Repository\TvaRepository;
use App\Service\ProduitService;
use App\Service\Utile\UploadFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitServiceImpl extends AbstractController  implements ProduitService
{
    private $uploadFile;
    private $categorieRepository;
    private $tvaRepository;
    private $produitRepository;
    private $promotionRepository;
    public function __construct(PromotionRepository $promotionRepository, ProduitRepository $produitRepository, TvaRepository $tvaRepository, UploadFile $uploadFile, CategorieRepository $categorieRepository)
    {
        $this->uploadFile = $uploadFile;
        $this->categorieRepository = $categorieRepository;
        $this->tvaRepository = $tvaRepository;
        $this->produitRepository = $produitRepository;
        $this->promotionRepository = $promotionRepository;
    }


    public function validate($request,  $p)
    {
        $data = $request->request->all();
        $description = $data['description'];
        $information = $data['information'];
        $dateProduction = $data['dateProduction'];
        $prixU = $data['prixU'];
        $stock = $data['stock'];
        $libelle = $data['libelle'];
        $promotion = $data['promotion'];
        $dateDebut = $data['dateDebut'];
        $dateFin = $data['dateFin'];
        $produit = new Produit();
        $produit = $p;
        $message = "";
        if (empty($description)) {
            $produit->setDescription("");
            $message = "- La description est vide ! <br> ";
        } else {
            $produit->setDescription($description);
        }
        if (empty($information)) {
            $produit->setInformation("");
            $message = $message . "- L'information est vide !  <br>";
        } else {
            $produit->setInformation($information);
        }
        if (empty($libelle)) {
            $produit->setLibelle("");
            $message = $message . "- Libelle est vide !  <br>";
        } else {
            $produit->setLibelle($libelle);
        }
        if (empty($dateProduction) || date($dateProduction) > date('Y-m-d')) {
            $message = $message . "- La date de p roduction  est vide ou  plus grande que la date d’aujourd’hui ! <br> ";
        } else {
            $produit->setDateProduction(\DateTime::createFromFormat('Y-m-d', $dateProduction));
        }

        if (empty($prixU) || !is_numeric($prixU)) {
            $message = $message . "- Prix unitaire  est vide ou  n'est pas un nombre!  <br>";
        } else {
            if ($prixU <= 0) {
                $message = $message . "- Prix unitaire il doit être supérieur à zéro!  <br>";
            } else {
                $produit->setPrixUnitaire($prixU);
            }
        }



        if (empty($stock) || !is_numeric($stock)) {
            $message = $message . "- Stock  est vide ou  n'est pasun nombre! <br> ";
        } else {
            if ($stock <= 0) {
                $message = $message . "- Stock  il doit être supérieur à zéro!  <br>";
            } else {
                $produit->setStock($stock);
            }
        }

        if (empty($promotion)) {
            $produit->getPromotion()->setReduction(0);
            $promotion = 0;
        }



        if (!empty($promotion) && !is_numeric($promotion)) {
            $message = $message . "- Promotion    n'est pas un nombre!  <br>";
        } else {
            if ($promotion < 0) {
                $message = $message . "- Promotion elle doit être supérieur à zéro!  <br>";
            }
        }
        if (!empty($promotion) && is_numeric($promotion)) {
            $produit->getPromotion()->setReduction($promotion);
        }
        if (!empty($dateDebut) && !empty($dateFin)) {
            $produit->getPromotion()->setDateFin(\DateTime::createFromFormat('Y-m-d', $dateFin));
            $produit->getPromotion()->setDateDebut(\DateTime::createFromFormat('Y-m-d', $dateDebut));
        }
        if ($promotion == 0) {
            $produit->getPromotion()->setDateFin(new \DateTime());
            $produit->getPromotion()->setDateDebut(new  \DateTime());
        }

        if (!empty($dateDebut) && !empty($dateFin) && date($dateDebut) > date($dateFin)) {
            $message = $message . "- La  date de  fin doit être supérieur à date de début   !  <br> ";
        }
        $errors[] = [];
        array_push($errors, $produit);
        array_push($errors, $message);
        return $errors;
    }


    public function  editP($request,  $produit)
    {
        $retust =  $this->validate($request, $produit);
        if (!empty($retust[2])) {
            return $retust;
        } else { 
            $produit = $retust[1];
            $categorie = new Categorie();
            $categorie = $this->categorieRepository->findOneBy(['id' => $request->request->get('categorie')]);
            $produit->setCategorie($categorie);
            if ($request->request->has('bio')) {
                $produit->setIsBio(true);
            } else {
                $produit->setIsBio(false);
            }
            if ($request->files->get("file") != null) {
                $produit->setImage("img/product/" . $this->uploadFile->uploadFileToProduct($request));
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            $retust[2] = "";
            return $retust;
        }
    }


    public function  addP($market,  $request)
    {
        $produit = new Produit();
        $promotion = new Promotion();
        $produit->setPromotion($promotion);

        $retust =  $this->validate($request, $produit);
        if (!empty($retust[2])  || $request->files->get("file") == null) {

            $retust[2] = $retust[2] . "choisi une photo !";
            return $retust;
        } else {
            $produit = $retust[1];
            $categorie = new Categorie();
            $categorie = $this->categorieRepository->findOneBy(['id' => $request->request->get('categorie')]);
            $produit->setCategorie($categorie);
            $produit->setUnite($request->request->get('unite'));
            $produit->setMarket($market);
            if ($request->request->has('bio')) {
                $produit->setIsBio(true);
            } else {
                $produit->setIsBio(false);
            }

            if ($request->files->get("file") != null) {
                $produit->setImage("img/product/" . $this->uploadFile->uploadFileToProduct($request));
            }
            $tva = $this->tvaRepository->findAll();
            foreach ($tva as $t) {
                $produit->setTva($t);
            }
            $produit->setDateAjout(new  \DateTime);
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit->getPromotion());
            $em->flush();

            $em->persist($produit);
            $em->flush();
            $retust[2] = "";
            return $retust;
        }
    }

    // By Ali
    /**
     * pout calculer le prix de la reduction d'un produit s'il est appliqué 
     */
    public function getReduction(Produit $produit, int $idPromotion): float
    {

        $reduction = 0;
        $promotion = new Promotion;
        $promotion = $this->promotionRepository->find($idPromotion);

        if (($promotion->getDateDebut() < new  \DateTime) && ($promotion->getDateFin() > new  \DateTime)) {
            $reduction = $promotion->getReduction() * $produit->getPrixUnitaire() / 100;
        }

        return $reduction;
    }

    public function getPromotion(int $idPromotion): Promotion
    {
        return $this->promotionRepository->find($idPromotion);
    }
}
