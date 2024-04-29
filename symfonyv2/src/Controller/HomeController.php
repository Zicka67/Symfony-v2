<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/proteines', name: 'home_prot')]
    public function showProt(ProductsRepository $productsRepository): Response
    {
        $wheyProducts = $productsRepository->findBySlugContainingWhey();
        $bars = $productsRepository->findBar();

        return $this->render('produits/proteine.html.twig', [
            'controller_name' => 'HomeController',
            'wheyProducts' => $wheyProducts,
            'bars' => $bars
        ]);
    }

    #[Route('/barre', name: 'home_barre')]
    public function showBar(ProductsRepository $productsRepository): Response
    {
        $wheyProducts = $productsRepository->findBySlugContainingWhey();
        $textForCarrousel = $productsRepository->findAll();
        $bars = $productsRepository->findBar();
        // dd($bars);

        return $this->render('produits/barre.html.twig', [
            'controller_name' => 'HomeController',
            'wheyProducts' => $wheyProducts,
            'bars' => $bars,
            'textForCarrousel' => $textForCarrousel
        ]);
    }


}
