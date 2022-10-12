<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $repo;
    private $rs;

    // injection de dépendances hors d'un controller : constructeur
    public function __construct(ProductRepository $repo, RequestStack $rs)// RequestStack permet de récupérer les données de la session
    {
        $this->repo = $repo;
        $this->rs = $rs;
    }

    public function add($id)
    {
        // nous allons récupérer ou créer une session (si elle n'existe pas) grâce à la classe RequestStack
        $session = $this->rs->getSession();

        $cart = $session->get('cart', []);
        // je récupère l'attribut de session 'cart' s'il existe ou un tableau vide

        // si le produit existe déjà, j'incrémente sa quantité
        if (!empty($cart[$id])) 
        {
            $cart[$id]++;
            // équivaut à $cart[$id] = $cart[$id]+1
        }
        else
        {
            $cart[$id] = 1;
            // dans mon tableau $cart, à la case $id, j'insère la valeur 1
        }

        $session->set('cart', $cart);
        // je sauvegarde l'état de mon panier en session à l'attribut de session 'cart'
    }

    public function remove($id)
    {
        $session = $this->rs->getSession();
        $cart = $session->get('cart', []);

        // si l'id existe, je le supprime du tableau
        if (!empty($cart[$id]))
        {
            unset($cart[$id]);
        }
        
        $session->set('cart', $cart);

    }

    public function getCartWithData()
    {
        $session = $this->rs->getSession();
        $cart = $session->get('cart', []);

        // nous allons créer un nouveau tableau qui contiendra des objets Product et les quantités de chaque objet
        $cartWithData =[];

        foreach ($cart as $id => $quantite) 
        {
            $cartWithData[] =[
                'product' => $this->repo->find($id),
                'quantite' => $quantite,
            ];
        }

        return $cartWithData;
    }
    
    public function getTotal()
    {
        $cartWithData = $this->getCartWithData();
        $total = 0;
        
        // dd($cartWithData);
        foreach ($cartWithData as $item) 
        {
            $totalUnitaire = $item['product']->getPrix() * $item['quantite'];
            $total += $totalUnitaire;
        }
        
        return $total;
    }
}