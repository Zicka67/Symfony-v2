<?php

namespace App\Controller;

use App\Entity\Flavor;
use App\Entity\Products;
use App\Repository\FlavorRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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
    public function showProt(ProductsRepository $productsRepository, FlavorRepository $flavorRepository): Response
    {

        $wheyProducts = $productsRepository->findBySlugContainingWhey();
        $bars = $productsRepository->findBar();
        // dd($bars);
        // dd($flavor);

        return $this->render('produits/proteine.html.twig', [
            'controller_name' => 'HomeController',
            'wheyProducts' => $wheyProducts,
            'bars' => $bars
        ]);
    }

    #[Route('/barre', name: 'home_barre')]
    public function showBar(ProductsRepository $productsRepository, FlavorRepository $flavorRepository): Response
    {

        $wheyProducts = $productsRepository->findBySlugContainingWhey();
        $bars = $productsRepository->findBar();
        // dd($bars);

        return $this->render('produits/barre.html.twig', [
            'controller_name' => 'HomeController',
            'wheyProducts' => $wheyProducts,
            'bars' => $bars
        ]);
    }


}
