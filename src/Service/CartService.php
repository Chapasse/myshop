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
        $session = $this->rs->getSession();

        $cart = $session->get('cart', []);

        if (!empty($cart[$id])) 
        {
            $cart[$id]++;
        }
        else
        {
            $cart[$id] = 1;
        }
        // if(!empty($cart[$id])):
        //     if($addQuantite > 1):
        //         $cart[$id] += $addQuantite;
        //     else:
        //         $cart[$id]++;
        //     endif;
        // else:
        //     $cart[$id] = $addQuantite;
        // endif;

        $session->set('cart', $cart);
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
    
    public function decrease($id)
    {
        $session = $this->rs->getSession();
        $cart = $session->get('cart', []);
 
        if ($cart[$id] > 1) 
        {
            $cart[$id]--;
        } else {
 
            unset($cart[$id]);
             
        }
        return $session->set('cart', $cart);
    }
 
}