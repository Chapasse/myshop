<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ProductRepository $repo): Response
    {
        
        return $this->render('product/index.html.twig', [
            'products' => $repo->findAll(),
        ]);
    }

    #[Route('/show/{id}', name:'show')]
    public function show($id, ProductRepository $repo)
    {
        $product = $repo->find($id);
        return $this->render("product/show.html.twig",[
            "product" => $product,
            "id" => $product->getId()
        ]);
    }

    #[Route('/profil', name: 'profil')]
    public function profil(CommandeRepository $repo)
    {
        $commandes = $repo->findBy(['id_membre' => $this->getUser()]);

        return $this->render("product/profil.html.twig", [
            'commandes' => $commandes
        ]);
    }
}
