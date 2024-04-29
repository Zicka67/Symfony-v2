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
    $total = $this->calculateCartTotal($session, $productsRepository);
    // dd($total);
    $deliveryCost = 4.50; 

    foreach ($cart as $id => $quantity) {
        $cartWithData[] = [
            'product' => $productsRepository->find($id),
            'quantity' => $quantity
        ];
    }

    // Vérification pour le code du coupon
    $couponCode = $request->request->get('coupon_code');

   
    if ($couponCode) {
        $coupon = $couponRepository->findOneBy(['code' => $couponCode, 'is_valid' => true]);
        if ($coupon) {
            $discountValue = $total * ($coupon->getDiscount() / 100);

            $total -= $discountValue;
            // Enregistrer le coupon et la nouvelle valeur totale dans la session
            $session->set('cart_total_with_discount', $total);
            $session->set('coupon', $couponCode);
        } else {
            // Ajouter un message flash pour indiquer que le coupon est invalide
            // $this->addFlash('danger', 'Le coupon n\'est pas valide.');
        }
        
    }
    // var_dump($request->isXmlHttpRequest());
    $cartTotalWithDiscount = $session->get('cart_total_with_discount') ?? $total;
    // var_dump($request->isXmlHttpRequest());

    // Si la requête est une requête AJAX, renvoie uniquement le prix total mis à jour en réponse JSON
    if ($request->isXmlHttpRequest()) {
        $response = new JsonResponse();
        $response->setData([
            'total' => $cartTotalWithDiscount
        ]);
        return $response;
    }

    // Sinon, continuez avec le rendu de la vue normalement
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
    public function increase($id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if (!empty($cart[$id])) {
            $cart[$id]++;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart_show');
    }

    #[Route('/cart/decrease/{id}', name: 'cart_decrease')]
    public function decrease($id, SessionInterface $session): Response
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

    // #[Route('/cart/apply-coupon', name: 'cart_apply_coupon', methods: ['POST'])]
    // public function applyCoupon(Request $request, SessionInterface $session, ProductsRepository $productsRepository, CouponRepository $couponRepository): Response
    // {
    //     $couponCode = $request->request->get('coupon_code');
    //     $coupon = $couponRepository->findOneBy(['code' => $couponCode, 'is_valid' => true]);
        
    //     if (!$coupon) {
    //         $this->addFlash('danger', 'Le coupon n\'est pas valide.');
    //         return $this->redirectToRoute('cart_show');
    //     }

    //     $total = $this->calculateCartTotal($session, $productsRepository);
    //     $discountValue = $total * ($coupon->getDiscount() / 100);
    //     $newTotal = $total - $discountValue;

    //     $session->set('cart_total_with_discount', $newTotal);
    //     $session->set('coupon', $couponCode);
        
    //     $this->addFlash('success', 'Le coupon a été appliqué avec succès!');
        
    //     return $this->redirectToRoute('cart_show');
    // }

    

}
