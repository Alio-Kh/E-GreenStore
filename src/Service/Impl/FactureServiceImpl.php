<?php

namespace App\Service\Impl;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Facture;
use App\Entity\TypeLivraison;
use App\Repository\ClientRepository;
use App\Repository\LivraisonRepository;
use App\Repository\TypeLivraisonRepository;
use App\Repository\UserRepository;
use App\Service\CommandeService;
use App\Service\FactureService;
use App\Service\PanierService;
use App\Service\ProduitService;
// FPDF
use FPDF;
use App\Service\Impl\PDF;
use DateInterval;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
//DOMPDF
use Dompdf\Dompdf;
use Dompdf\Options;


class FactureServiceImpl extends AbstractController implements FactureService
{
    private $agriculteurRepository;
    private $panierService;
    private $produitService;
    private $clientRepository;
    private $livraisonRepository;
    private $userRepository;
    private $mailer;
    private $typeLivraisonRepository;


    public function __construct(
        TypeLivraisonRepository $typeLivraisonRepository,
        UserRepository $userRepository,
        MailerInterface $mailer,
        PanierService $panierService,
        ClientRepository $clientRepository,
        ProduitService $produitService,
        LivraisonRepository $livraisonRepository
    ) {
        $this->panierService = $panierService;
        $this->produitService = $produitService;
        $this->clientRepository = $clientRepository;
        $this->livraisonRepository = $livraisonRepository;
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
        $this->typeLivraisonRepository = $typeLivraisonRepository;
    }

    public function makeReference(): string
    {
        $date = date_format(new \DateTime, 'm-y');
        return strval(random_int(0, 1000)) . '/' . $date;
    }

    public function save(Commande $commande, $data)
    {
        $panier = $this->panierService->getFullPanier();

        $facture = new Facture;
        $facture->setDateCreation(new \DateTime());
        $facture->setCommande($commande);
        $facture->setPaiement(null);
        $facture->setReference($this->makeReference());

        $em = $this->getDoctrine()->getManager();
        $this->createFacturePdf($facture, $commande, $data);
        $em->persist($facture);
        $em->flush();
    }

    public function createFacturePdf(Facture $facture, Commande $commande, $data)
    {
        $panier = $this->panierService->getFullPanier();
        $client = $commande->getClient();
        $user = $this->userRepository->findOneBy(['client' => $client]);

        $typeLivraisonId = $data['shipping'];
        $typeLivraison = new TypeLivraison;
        $typeLivraison = $this->typeLivraisonRepository->find($typeLivraisonId);

        $total = $this->panierService->getTotal();
        $tva = $total * 0.2;
        $totalTtc = $tva + $total;

        // START DOMPDF:
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $date = new \DateTime;

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('pdfs/facture.html.twig', [
            'title' => "Facture",
            'client' => $client,
            'clientEmail' => $user->getEmail(),
            'commande' => $commande,
            'facture' => $facture,
            'panier' => $panier,
            'totalTtc' => $totalTtc,
            'tva' => $tva,
            'total' =>  $total,
            'date' => date_format($date, 'Y-m-d'),
            'dateLivraison' => date_format($date->add(new DateInterval('P' . $typeLivraison->getDuree() / 24 . 'D')), 'Y-m-d'),
            'fraisLivraison' => $typeLivraison->getFrais(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        // $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        // dd($commande);

        // Mailer
        $this->sendMailFacture($commande, $facture, $data);

        $dompdf->stream("facture.pdf", ['Attachment' => 1]);
        // $output = $dompdf->output();

        // // In this case, we want to write the file in the public directory
        // $publicDirectory = $this->get('kernel')->getProjectDir() . '/public/facture';
        // // e.g /var/www/project/public/mypdf.pdf
        // $pdfFilepath =  $publicDirectory . '/facture.pdf';

        // // Write file to the desired path
        // file_put_contents($pdfFilepath, $output);

        // END DOMPDF

        // dd($panier);

        // return new Response($pdf->Output(), 200, array(
        //     'Content-Type' => 'application/pdf'
        // ));
    }



    public function sendMailFacture($commande, $facture, $data)
    {

        $panier = $this->panierService->getFullPanier();
        $user = $this->userRepository->findOneBy(['client' => $commande->getClient()]);

        $total = $this->panierService->getTotal();
        $tva = $total * 0.2;
        $totalTtc = $tva + $total;

        $date = new \DateTime;
        $typeLivraisonId = $data['shipping'];
        $typeLivraison = new TypeLivraison;
        $typeLivraison = $this->typeLivraisonRepository->find($typeLivraisonId);


        $email = (new TemplatedEmail())
            ->From('vente_service@greenStore.ma')
            ->To($user->getEmail())
            ->Subject('Facture')
            ->htmlTemplate('emails/facture.html.twig')
            ->context([
                'client' => $commande->getClient(),
                'clientEmail' => $user->getEmail(),
                'commande' => $commande,
                'facture' => $facture,
                'panier' => $panier,
                'totalTtc' => $totalTtc,
                'tva' => $tva,
                'total' =>  $total,
                'date' => date_format(new \DateTime, 'd/m/Y'),
                'dateLivraison' => date_format($date->add(new DateInterval('P' . $typeLivraison->getDuree() / 24 . 'D')), 'Y-m-d'),
                'fraisLivraison' => $typeLivraison->getFrais(),
            ]);
        $this->mailer->send($email);
    }
}
