<?php

namespace App\Service\Utile;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadFile extends AbstractController
{
    private $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function  uploadFileToMarkets($request)
    {
            $file  = $request->files->get("file");
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('market_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
            }
            return $newFilename;
        
    }
    public function  uploadFileToProduct( $request){
        $file  = $request->files->get("file");
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        try {
            $file->move(
                $this->getParameter('brochures_directory'),
                $newFilename
            );
        } catch (FileException $e) {
        }
        return $newFilename;
    }
}
