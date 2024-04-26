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
        $bars = $productsRepository->findBar();
        // dd($bars);

        return $this->render('produits/barre.html.twig', [
            'controller_name' => 'HomeController',
            'wheyProducts' => $wheyProducts,
            'bars' => $bars
        ]);
    }

    private function calculateCartTotal(SessionInterface $session, ProductsRepository $productsRepository)
    {
        $cart = $session->get('cart', []);
        $total = 0;

        foreach ($cart as $id => $quantity) {
            $product = $productsRepository->find($id);
            if ($product) {
                $total += ($product->getPrice() * $quantity);
            }
        }

        return $total;
    }

    #[Route('/cart', name: 'cart_show')]
    public function show(SessionInterface $session, ProductsRepository $productsRepository): Response
    {
        $cart = $session->get('cart', []);
        $cartWithData = [];
        $total = $this->calculateCartTotal($session, $productsRepository);

        foreach ($cart as $id => $quantity) {
            $cartWithData[] = [
                'product' => $productsRepository->find($id),
                'quantity' => $quantity
            ];
        }

        return $this->render('cart/index.html.twig', [
            'items' => $cartWithData,
            'total' => $total
        ]);
    }

    

}
