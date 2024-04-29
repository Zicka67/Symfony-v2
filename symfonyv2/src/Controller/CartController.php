<?php

namespace App\Controller;

use App\Repository\CouponRepository;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{

    #[Route('/cart', name: 'cart_show', methods: ['GET', 'POST'])]
public function show(SessionInterface $session, ProductsRepository $productsRepository, Request $request, CouponRepository $couponRepository ): Response
{
    $cart = $session->get('cart', []);
    
    $cartWithData = [];
    foreach ($cart as $id => $quantity) {
        $cartWithData[] = [
            'product' => $productsRepository->find($id),
            'quantity' => $quantity
        ];
    }

    $total = $this->calculateCartTotal($session, $productsRepository);
    $deliveryCost = 4.50; 

    // Vérification pour le code du coupon si c'est une requête AJAX
    if ($request->isXmlHttpRequest()) {
        $couponCode = $request->request->get('coupon_code');
        $couponTotalWithDiscount = $total;
        
        if ($couponCode) {
            $coupon = $couponRepository->findOneBy(['code' => $couponCode, 'is_valid' => true]);
            
            if ($coupon) {
                $discountValue = $total * ($coupon->getDiscount() / 100);
                $couponTotalWithDiscount -= $discountValue;
            } else {
                // Si le coupon n'est pas valide, vous pouvez choisir de renvoyer une erreur ou simplement ignorer le coupon
                return new JsonResponse(['error' => 'Coupon invalide'], 400);
            }
        }

        $totalWithDelivery = $couponTotalWithDiscount + ($deliveryCost * 100);

        // Enregistrer le coupon et la nouvelle valeur totale dans la session
        $session->set('cart_total_with_discount', $couponTotalWithDiscount);
        $session->set('coupon', $couponCode);

        return new JsonResponse([
            'total' => number_format($couponTotalWithDiscount / 100, 2, '.', ''),
            'totalWithDelivery' => number_format($totalWithDelivery / 100, 2, '.', '')
        ]);
    }

    $cartTotalWithDiscount = $session->get('cart_total_with_discount', $total);

    // Rendu de la vue normalement pour les requêtes non AJAX
    return $this->render('cart/index.html.twig', [
        'items' => $cartWithData,
        'total' => $total,
        'cartTotalWithDiscount' => $cartTotalWithDiscount,
        'deliveryCost' => $deliveryCost
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

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add($id, SessionInterface $session, ProductsRepository $productsRepository): Response
    {
        $cart = $session->get('cart', []);

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('home_prot');
    }

    #[Route('/cart/increase/{id}', name: 'cart_increase')]
    public function increase($id, SessionInterface $session, ProductsRepository $productsRepository, Request $request): Response
    {
        $cart = $session->get('cart', []);
        $product = $productsRepository->find($id);
        
        if ($product) {
            if (isset($cart[$id])) {
                $cart[$id]++;
            } else {
                $cart[$id] = 1;
            }

            $session->set('cart', $cart);

            if ($request->isXmlHttpRequest()) {
                $total = $this->calculateCartTotal($session, $productsRepository);
                $deliveryCost = 4.50;
                $productPrice = $product->getPrice() * $cart[$id]; 

                return new JsonResponse([
                    'total' => number_format($total / 100, 2),
                    'totalWithDelivery' => number_format(($total + ($deliveryCost * 100)) / 100, 2),
                    'productId' => $id,
                    'productPrice' => number_format($productPrice / 100, 2) ,
                    'productQuantity' => $cart[$id],
                ]);
            }
        }

        return $this->redirectToRoute('cart_show'); 
    }

    #[Route('/cart/decrease/{id}', name: 'cart_decrease')]
    public function decrease($id, SessionInterface $session, ProductsRepository $productsRepository, Request $request): Response
    {
        $cart = $session->get('cart', []);
        $product = $productsRepository->find($id);
        
        if ($product) {
            if (!empty($cart[$id]) && $cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }

            $session->set('cart', $cart);

            if ($request->isXmlHttpRequest()) {
                $total = $this->calculateCartTotal($session, $productsRepository);
                $deliveryCost = 4.50;
                $productPrice = isset($cart[$id]) ? $product->getPrice() * $cart[$id] : 0; 

                return new JsonResponse([
                    'total' => number_format($total / 100, 2),
                    'totalWithDelivery' => number_format(($total + ($deliveryCost * 100)) / 100, 2),
                    'productId' => $id,
                    'productPrice' => number_format($productPrice / 100, 2), 
                    'productQuantity' => $cart[$id]
                ]);
            }
        }

        return $this->redirectToRoute('cart_show'); 
    }


    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove($id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if (!empty($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('home_prot');
    }

    #[Route('/cart/remove-all/{id}', name: 'cart_remove_all')]
    public function removeAll($id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart_show');
    }

    

}
