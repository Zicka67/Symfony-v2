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
    let autoSlideInterval = setInterval(moveToNextSlide, 8000); 
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
  