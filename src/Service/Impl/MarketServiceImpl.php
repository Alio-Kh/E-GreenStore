<?php

namespace App\Service\Impl;

use App\Entity\Agriculteur;
use App\Entity\Market;
use App\Repository\AgriculteurRepository;
use App\Repository\MarketRepository;
use App\Service\MarketService;
use App\Service\Utile\UploadFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MarketServiceImpl extends AbstractController implements MarketService
{
     private $agriculteurRepository;
     private $uploadFile;
     private  $marketRepository;

    public function __construct(MarketRepository $marketRepository,UploadFile $uploadFile,AgriculteurRepository $agriculteurRepository)
    {    $this->uploadFile=$uploadFile;
         $this->agriculteurRepository=$agriculteurRepository;
         $this->marketRepository=$marketRepository;
    }
    
    public function addM($request){
        $data = $request->request->all();
        $description = $data['description'];
        $libelle = $data['libelle'];
        $market = new Market();
        $message = "";
        if (empty($description)) {
            $market->setDescription("");
            $message = "- La description est vide ! <br> ";
        } else {
            $market->setDescription($description);
        }
       
        if (empty($libelle)) {
            $market->setLibelle("");
            $message = $message . "- Libelle est vide !  <br>";
        } else {
            $market->setLibelle($libelle);
        }
        $errors[] = [];
        if(!empty($message)){
             $message=$message."- choisi une image !";
             array_push($errors, $market);
             array_push($errors, $message);
            return $errors;
        }else{
            if ($request->files->get("file") != null) {
                $market->setImage("img/markets/".$this->uploadFile->uploadFileToMarkets($request));
                $agriculteur = new Agriculteur();
                $agriculteur = $this->agriculteurRepository->findOneBy(array('id' => $this->getUser()->getAgriculteur()->getId()));
                $market->setAgriculteur($agriculteur);
                $em = $this->getDoctrine()->getManager();
                $em->persist($market);
                $em->flush();
                array_push($errors, $market);
                array_push($errors, $message);
                return $errors;
 
            }else{
                $message=$message."choisi une image";
                array_push($errors, $market);
                array_push($errors, $message);
                return $errors;

            }
        }
    }
    
    public function editM($request,$market){
        $data = $request->request->all();
        $description = $data['description'];
        $libelle = $data['libelle'];
        $message = "";
        if (empty($description)) {
            $market->setDescription("");
            $message = "- La description est vide ! <br> ";
        } else {
            $market->setDescription($description);
        }
       
        if (empty($libelle)) {
            $market->setLibelle("");
            $message = $message . "- Libelle est vide !  <br>";
        } else {
            $market->setLibelle($libelle);
        }
        $errors[] = [];
        if(!empty($message)){
             array_push($errors, $market);
             array_push($errors, $message);
            return $errors;
        }else{
            if ($request->files->get("file") != null) {
                $market->setImage("img/markets/".$this->uploadFile->uploadFileToMarkets($request));
                $em=$this->getDoctrine()->getManager();
                $em->persist($market);
                 $em->flush();
                array_push($errors, $market);
                array_push($errors, $message);
                return $errors;
            }else{
                $em=$this->getDoctrine()->getManager();
                $em->persist($market);
                $em->flush();
                array_push($errors, $market);
                array_push($errors, $message);
                return $errors;

            }
        }
       

    }

}