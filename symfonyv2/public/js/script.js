document.addEventListener('DOMContentLoaded', (event) => {
    
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const slide = document.querySelector('.carousel-slide');
    const images = document.querySelectorAll('.carousel-slide img');
    
    const textMain = document.getElementById("carousel-text-main");
    const textsMain = [
      "Vente flash d'avril", 
      "Nouveau beurre de cacahuète", 
      "Le plein de protéines, un pur plaisir"
    ];

    const text = document.getElementById("carousel-text-secondary");
    const texts = [
      "Du 25 au 28 avril, profite de 25% sur tous les produits de notre site.", 
      "Nouveau beurre de Cacachuète CROQUANT ET SALE: délicieux et rempli de protéine.", 
      "Vos besoins journaliers en protéines avec notre poudre de lactosérum."
    ];
 
    function updateText() {
      text.textContent = texts[counter];
    }

    function updateTextMain() {
      textMain.textContent = textsMain[counter];
    }
    
  
    let counter = 0;
    const size = images[0].clientWidth;
    let autoSlideInterval = setInterval(moveToNextSlide, 6000); 
    let direction = 1; // 1 pour avancer, -1 pour reculer

   
  
    function moveToNextSlide() {
      if (counter >= images.length - 1) { // Si on est à la dernière image
        direction = -1; // Changer la direction pour rembobiner
      } else if (counter <= 0 && direction === -1) { // Si on est revenu à la première image
        direction = 1; // Réinitialise la direction pour avancer
      }
  
      slide.style.transition = "transform 1s ease-in-out";
      counter += direction;
      slide.style.transform = 'translateX(' + (-size * counter) + 'px)';

      updateText();
      updateTextMain(); // Met à jour le texte à chaque transition
    }
  
    function stopAutoSlide() {
      clearInterval(autoSlideInterval);
    }
  
    nextBtn.addEventListener('click', () => {
      stopAutoSlide(); // Arrête le défilement automatique lorsque l'utilisateur interagit
      moveToNextSlide();
      updateText();
      updateTextMain();
    });
  
    prevBtn.addEventListener('click', () => {
      stopAutoSlide(); //Arrête le défilement automatique lorsque l'utilisateur interagit
      if (counter <= 0) return;
      counter--;
      slide.style.transition = "transform 0.4s ease-in-out";
      slide.style.transform = 'translateX(' + (-size * counter) + 'px)';
      updateText();
      updateTextMain();
    });
  
  });


  // ***********************  

  document.addEventListener('DOMContentLoaded', (event) => {
    // Attache les gestionnaires d'événements aux boutons pour augmenter, diminuer, et supprimer des produits
    attachEventListeners('.quantity-modify.increase', 'href');
    attachEventListeners('.quantity-modify.decrease', 'href');
    attachEventListeners('.delete-item', 'href');

    function attachEventListeners(selector, attribute) {
        document.querySelectorAll(selector).forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Empêche le comportement par défaut
                handleQuantityChange(this.getAttribute(attribute)); // Utilise l'attribut pour obtenir l'URL
            });
        });
    }

    function handleQuantityChange(url) {
        fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(updateDOMElements)
        .catch(error => console.error('Erreur:', error));
    }

    function updateDOMElements(data) {

        document.getElementById('price').innerHTML = `Total du panier: <strong>${data.total} €</strong>`;
        document.getElementById('priceWithDelivery').innerHTML = `Total (TTC) <strong>${data.totalWithDelivery} €</strong>`;
    
        if (data.productId) {
            const productPriceElement = document.getElementById('product-price-' + data.productId);
            const productQuantityElement = document.getElementById('product-quantity-' + data.productId);

            if (productPriceElement) {
                productPriceElement.innerHTML = `${data.productPrice} €`;
            }

            if (productQuantityElement) {
                // console.log('Quantité avant mise à jour:', productQuantityElement.textContent);
                productQuantityElement.textContent = data.productQuantity;
                // console.log('Quantité après mise à jour:', productQuantityElement.textContent);
            }
            if (data.productPrice === '0.00') {
                const productRow = document.getElementById('product-row-' + data.productId);
                if (productRow) {
                    productRow.remove();
                }
            }
            if (data.removeProduct) {
              const productRow = document.getElementById('product-row-' + data.productId);
              if (productRow) {
                  productRow.remove();
              }
          }
        }
    }

      // Gestion de la soumission du formulaire de coupon
  document.getElementById('coupon-form').addEventListener('submit', function(event) { 
    event.preventDefault(); // Empêche le formulaire de se soumettre normalement
    var couponCode = document.getElementById('coupon-code').value;
    var data = new FormData();
    data.append('coupon_code', couponCode);

    fetch('/cart', {
        method: 'POST',
        body: data,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.total !== undefined && data.totalWithDelivery !== undefined) {
            document.getElementById('priceWithDelivery').innerHTML = 'Total (TTC) <strong>' + data.totalWithDelivery + ' €</strong>';
            document.getElementById('price').innerHTML = 'Total du panier: <strong>' + data.total + ' €</strong>';
        } else {
            console.error('Des données nécessaires sont manquantes dans la réponse JSON');
        }
    })
    .catch(error => console.error('Erreur:', error));
  });


});











  