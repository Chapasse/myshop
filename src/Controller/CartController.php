<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Service\CartService;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    #[Route('/cart/commande', name: 'app_commande')]
    public function index(CartService $cs, EntityManagerInterface $manager, Request $globals): Response
    {
        $cartWithData = $cs->getCartWithData();
        // dd($cartWithData);
        $total = $cs->getTotal();
        // dd($cartWithData);
        $routeName = $globals->get('_route');
        if($routeName == 'app_commande')
        {
            foreach ($cartWithData as $product ) 
            {
                $commande = new Commande;
                $quantite=$product['quantite'];
                $stock=$product['product']->getStock();
                
                $prix = $product['quantite'] * $product['product']->getPrix();
                $commande->setDateEnregistrement(new \DateTime);
                if ($quantite > $stock) {
                    $commande->setQuantite($stock);
                    $this->addFlash('warning', 'Nous ne pouvons pas assurer la totalité de votre commande mais uniquement '.$stock.' Tshirts que nosu avons en stock');
                } else {
                    $commande->setQuantite($product['quantite']);
                }
                if ($quantite > $stock):
                    $quantite = $stock;
                endif;
                $commande->setMontant($prix);
                $commande->setEtat('en cours de traitement');
                $commande->setIdProduct($product['product']);
                $commande->setIdMembre($this->getUser());
                $stock= $stock-$quantite;
                $product['product']->setStock($stock);
                $manager->persist($commande);
                $manager->flush();
                $cs->remove($product['product']->getId());
                
            }
            $this->addFlash('success', 'Votre commande a bein été enregistrée');
            return $this->redirectToRoute('home');
        }

        return $this->render('cart/index.html.twig', [
            'items' => $cartWithData,
            'total' => $total
        ]);
    }

    #[Route("/cart/add/{id}", name:"cart_add")]
    public function add($id, CartService $cs) 
    {
        $cs->add($id);        
        return $this->redirectToRoute('app_cart');
    }

    #[Route("/cart/remove/{id}", name:"cart_remove")]
    public function remove($id, CartService $cs)
    {
        $cs->remove($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route("/cart/decrease/{id}", name:"cart_decrease")]
    public function decrease($id, CartService $cs)
    {
        $cs->decrease($id);
        return $this->redirectToRoute('app_cart');
    }

}
