<?php

namespace App\Controller;

use App\Entity\Flavor;
use App\Entity\Products;
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
    public function showProt(EntityManager $em): Response
    {

        $produit = $em->getRepository(Products::class)->find(1);

        $gout = new Flavor();
        $gout->setFlavorName("Vanille");

        $produit->addGout($gout);

        $em->flush();


        return $this->render('produits/proteine.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
