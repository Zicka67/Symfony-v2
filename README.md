Site exercice, le but étant de réaliser un site existant déja https://www.foodspring.fr/

Les principaux points sont :
- Réalisation de la base de données ( et MCD MLD ) 
- Gestion du CRUD
- Système d'inscription et de connection, confirmation par mail
- Gestion du panier en Session
- Possibilité de payer son panier via Stripe et son API
- Une approche pour le front très proche du modèle

![FOODSPRING](https://raw.githubusercontent.com/Zicka67/Symfony-v2/master/symfonyv2/public/img/Foodspring.webp)


PANIER :

1- Récupération du panier depuis la session

*$cart = $session->get('cart', []);*

2- Ajouter un produit en panier

*if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }*

3- Calcul du total du panier

*$total = 0;
foreach ($cart as $id => $quantity) {
            $product = $productsRepository->find($id);
            if ($product) {
                $total += ($product->getPrice() * $quantity);
            }
        }*

 ![Capture d'écran du projet](https://github.com/Zicka67/Symfony-v2/blob/master/symfonyv2/public/img/Panier.png)
