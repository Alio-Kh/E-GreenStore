<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request ,CategorieRepository $categorieRepository,MailerInterface $mailer)
    {   
        $categories = $categorieRepository->findCategoryNotEmpty();
        $form = $this->createFormBuilder()
        ->add('nom', TextType::class)
        ->add('sujet', TextType::class)
        ->add('email', EmailType::class)
        ->add('message', TextareaType::class)
         ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = (new TemplatedEmail())
                ->From($data['email'])
                ->To('contact@greenstore.ma')
                ->Subject($data['sujet'])
                ->htmlTemplate('emails/contact.html.twig')
                ->context([
                    'data' =>$data,
                ]);
               $mailer->send($email);
               $this->addFlash('message','Votre email a bien ete envoye');
              return $this->redirectToRoute('contact');
        }
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'Contact ','categories'=>$categories,'form' => $form->createView()
        ]);
    }
}
