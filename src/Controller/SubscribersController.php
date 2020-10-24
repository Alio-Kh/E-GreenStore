<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Subscribers;
use App\Repository\SubscribersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class SubscribersController extends AbstractController
{
    /**
     * @Route("/subscribers", name="subscribers")
     */
    public function subscribers(Request $request, SubscribersRepository $subscribersRepository)
    {
        $email = $request->request->get('email');
        $subscriber = new Subscribers();
        $error = "empty";
        if (!empty($email)) {
            $isExit=$subscribersRepository->findOneBy(["email"=>$email]);
            if ($isExit) {
                $error= 'Vous avez dèja abonné';
                $data = $this->get('serializer')->serialize(
                    [
                        'error' =>$error,
                    ],
                    'json'
                );
                $response = new Response($data);
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error= 'Votre email n\'est pas valid, essayer avec une autre adresse';
                $data = $this->get('serializer')->serialize(
                    [
                        'error' =>$error,
                    ],
                    'json'
                );
                $response = new Response($data);
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
            
            $subscriber->setEmail($email);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subscriber);
            $entityManager->flush();
            $data = $this->get('serializer')->serialize(
                [
                    'error' =>$error,
                ],
                'json'
            );
            $response = new Response($data);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $error= 'S\'il vous plait entrer votre adresse email';
        $data = $this->get('serializer')->serialize(
            [
                'error' =>$error,
            ],
            'json'
        );
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
