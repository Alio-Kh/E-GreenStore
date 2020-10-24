<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Produit;
use App\Entity\RecommendationProduit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\RecommendationProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="produit_index", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="produit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="produit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Produit $produit): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="produit_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Produit $produit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produit_index');
    }
     /**
     * @Route("/commentaire", name="commentaire"  )
     */
    public function commentaire(Request $request, ProduitRepository $produitRepository)
    {
        if ($this->getUser()->getClient() == null) {
            $error = "notClient";
            $data = $this->get('serializer')->serialize(
                [
                    'error' => $error,
                ],
                'json'
            );
            $response = new Response($data);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        $c = $request->request->get('commentaire');
        $produit = $request->request->get('produit');
        $commentaire = new Commentaire();
        $commentaire->setClient($this->getUser()->getClient());
        $error = "empty";
        if (!empty($c)) {
            $produit = $produitRepository->findOneBy(array('id' => $produit));
            $commentaire->setProduit($produit);
            $commentaire->setCommentaire($c);
            $error = "valid";
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();
        }

        $data = $this->get('serializer')->serialize(
            [
                'commentaire' => $commentaire->getCommentaire(),
                'nom' => $commentaire->getClient()->getNom(),
                'prenom' => $commentaire->getClient()->getPrenom(),
                'error' => $error,
            ],
            'json'
        );
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

     /**
     * @Route("/{id}/like", name="produit_like")
     */
    public function like(Produit $produit, RecommendationProduitRepository $recommendationProduit): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['code' => 403, 'message' => 'vous devez être connecté'], 403);
        }
        $client=$user->getClient();
        if(!$client){
            return $this->json(['code' => 403, 'message' => 'Vous n\'êtes pas un client'], 403);
        }
        $entityManager = $this->getDoctrine()->getManager();
        if ($produit->isLikedByClient($user)) {
            $like = $recommendationProduit->findOneBy(['produit' => $produit, 'client' => $client]);
            $entityManager->remove($like);
            $entityManager->flush();
            return $this->json([
                'id'=>$produit->getId(),
                'code' => 200,
                'message' => 'like bien supprimer ',
                'likes' => $recommendationProduit->count(['produit' => $produit])
            ], 200);
        }
        $like = new RecommendationProduit();
        $like->setProduit($produit);
        $like->setClient($client);
        $entityManager->persist($like);
        $entityManager->flush();
        return $this->json([
            'id'=>$produit->getId(),
            'code' => 200,
            'message' => 'like bien ajouter ',
            'likes' => $recommendationProduit->count(['produit' => $produit])
        ], 200);
    }
}
