/**
 * Public JavaScript - Site Point Com
 * Gère les animations, interactions et comportements communs à toutes les pages
 */

// ============================================
// 1. DÉTECTION DES PRÉFÉRENCES DE MOUVEMENT
// ============================================

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
const shouldAnimate = !prefersReducedMotion.matches;

// Fonction pour désactiver les animations si nécessaire
function disableAnimationsIfNeeded() {
  if (!shouldAnimate) {
    document.documentElement.style.setProperty('--animation-duration', '0.01ms');
    document.documentElement.style.setProperty('--transition-duration', '0.01ms');
  }
}

// Appel initial
disableAnimationsIfNeeded();

// Écouter les changements de préférences
if (window.matchMedia) {
  prefersReducedMotion.addEventListener('change', (e) => {
    window.location.reload(); // Recharger pour appliquer les changements
  });
}

// ============================================
// 2. NAVBAR - EFFET AU SCROLL
// ============================================


// Initialiser au chargement du DOM

// ============================================
// 3. ANIMATIONS AU DÉFILEMENT (Intersection Observer)
// ============================================

function initScrollAnimations() {
  if (!shouldAnimate || !('IntersectionObserver' in window)) {
    // Si pas d'animation ou pas de support, tout rendre visible
    const revealElements = document.querySelectorAll('.reveal, [class*="animate--"]');
    revealElements.forEach(el => {
      el.classList.add('visible');
    });
    return;
  }

  // Configuration de l'Intersection Observer
  const revealOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.15
  };

  // Callback pour les éléments 'reveal'
  const revealCallback = (entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  };

  // Observer pour les éléments 'reveal'
  const revealObserver = new IntersectionObserver(revealCallback, revealOptions);

  // Sélectionner tous les éléments avec la classe 'reveal'
  const revealElements = document.querySelectorAll('.reveal');
  revealElements.forEach(el => {
    // Vérifier si l'élément a déjà des classes d'animation
    if (!el.classList.contains('visible')) {
      revealObserver.observe(el);
    }
  });

  // Gestion des animations CSS personnalisées
  const animateElements = document.querySelectorAll('[class*="animate--"]');
  
  const animateCallback = (entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        // Ajouter une classe pour déclencher l'animation
        entry.target.classList.add('animate-triggered');
        // On peut aussi ajouter la classe visible si elle existe
        if (entry.target.classList.contains('reveal')) {
          entry.target.classList.add('visible');
        }
        observer.unobserve(entry.target);
      }
    });
  };

  // Observer pour les animations CSS
  const animateObserver = new IntersectionObserver(animateCallback, revealOptions);
  
  animateElements.forEach(el => {
    if (!el.classList.contains('visible') && !el.classList.contains('animate-triggered')) {
      animateObserver.observe(el);
    }
  });
}

// Initialiser les animations au scroll
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initScrollAnimations);
} else {
  initScrollAnimations();
}

// ============================================
// 4. ANIMATIONS POUR LES CARTES
// ============================================

function initCardAnimations() {
  if (!shouldAnimate) return;

  const cards = document.querySelectorAll('.card, .card-rubrique, .formation-card, .news-card');
  
  // Ajouter des délais d'animation basés sur l'index
  cards.forEach((card, index) => {
    // Calculer le délai (0.05s par carte, max 0.5s)
    const delay = Math.min(index * 0.05, 0.5);
    card.style.animationDelay = `${delay}s`;
    
    // Ajouter des classes utilitaires pour le délai
    if (delay > 0) {
      card.classList.add(`card-delay-${Math.round(delay * 10)}`);
    }
  });

  // Effet de parallaxe subtile pour les cartes
  if ('requestAnimationFrame' in window) {
    const cardsWithImage = document.querySelectorAll('.card-image, .formation-card, .news-card');
    
    function handleParallax() {
      const scrollPosition = window.pageYOffset;
      
      cardsWithImage.forEach(card => {
        const rect = card.getBoundingClientRect();
        const distanceFromTop = rect.top + scrollPosition;
        const cardHeight = rect.height;
        
        // Calculer le décalage (max 30px)
        const offset = (scrollPosition - distanceFromTop) * 0.05;
        const constrainedOffset = Math.max(-30, Math.min(30, offset));
        
        // Appliquer uniquement si la carte est visible ou proche
        if (rect.top < window.innerHeight + 100 && rect.bottom > -100) {
          const img = card.querySelector('img');
          if (img) {
            img.style.transform = `translateY(${constrainedOffset}px)`;
          }
        }
      });
    }

    let parallaxTimeout;
    window.addEventListener('scroll', () => {
      if (parallaxTimeout) {
        window.cancelAnimationFrame(parallaxTimeout);
      }
      parallaxTimeout = window.requestAnimationFrame(handleParallax);
    }, { passive: true });
  }
}

// Initialiser les animations de cartes
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initCardAnimations);
} else {
  initCardAnimations();
}

// ============================================
// 5. VIDÉO - Chargement au clic (déjà existant, amélioré)
// ============================================

function initVideoPlayers() {
  const videoBoxes = document.querySelectorAll('.video-clic');
  
  videoBoxes.forEach(box => {
    const playButton = box.querySelector('.video-play');
    if (!playButton) return;

    playButton.addEventListener('click', () => {
      const iframe = document.createElement('iframe');
      const url = new URL(box.dataset.src);
      url.searchParams.set('autoplay', '1');
      
      // Ajouter des paramètres additionnels pour YouTube/Vimeo
      if (box.dataset.src.includes('youtube.com') || box.dataset.src.includes('youtu.be')) {
        url.searchParams.set('rel', '0'); // Désactiver les vidéos liées
        url.searchParams.set('modestbranding', '1'); // Branding minimal
        url.searchParams.set('controls', '1'); // Contrôles activés
      }
      
      iframe.src = url.toString();
      iframe.title = box.dataset.titre || 'Vidéo';
      iframe.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
      iframe.allowFullscreen = true;
      iframe.className = 'video-iframe';
      
      // Animation de fondu
      iframe.style.opacity = '0';
      iframe.style.transition = 'opacity 0.3s ease';
      
      box.replaceChildren(iframe);
      
      // Attendre que l'iframe charge puis animer
      iframe.onload = () => {
        setTimeout(() => {
          iframe.style.opacity = '1';
        }, 50);
      };
    });
  });
}

// Initialiser les vidéos
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initVideoPlayers);
} else {
  initVideoPlayers();
}

// ============================================
// 6. GRAPHIQUES - Chargement dynamique (déjà existant)
// ============================================

function initCharts() {
  const charts = document.querySelectorAll('.js-chart');
  if (!charts.length) return;

  // Vérifier si Chart.js est déjà chargé
  if (typeof Chart !== 'undefined') {
    charts.forEach(c => {
      try {
        const config = JSON.parse(c.dataset.chart);
        new Chart(c, config);
      } catch (e) {
        console.error('Erreur lors du chargement du graphique:', e);
      }
    });
    return;
  }

  // Charger Chart.js dynamiquement
  const script = document.createElement('script');
  script.src = '/assets/vendor/chart.js/dist/chart.umd.min.js';
  script.async = true;
  script.onload = () => {
    charts.forEach(c => {
      try {
        const config = JSON.parse(c.dataset.chart);
        new Chart(c, config);
      } catch (e) {
        console.error('Erreur lors du chargement du graphique:', e);
      }
    });
  };
  script.onerror = () => {
    console.error('Échec du chargement de Chart.js');
  };
  document.head.appendChild(script);
}

// Initialiser les graphiques
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initCharts);
} else {
  initCharts();
}

// ============================================
// 7. EFFETS DE SURVOL POUR LES BOUTONS
// ============================================

function initButtonEffects() {
  // Boutons avec effet ripple
  const rippleButtons = document.querySelectorAll('.btn-animate-ripple');
  
  rippleButtons.forEach(button => {
    button.addEventListener('click', (e) => {
      // Créer l'effet ripple
      const ripple = document.createElement('span');
      ripple.className = 'btn-ripple';
      
      // Positionner au centre du bouton
      const rect = button.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      
      ripple.style.left = `${x}px`;
      ripple.style.top = `${y}px`;
      
      button.appendChild(ripple);
      
      // Animation du ripple
      setTimeout(() => {
        ripple.remove();
      }, 600);
    });
  });
}

// Initialiser les effets de boutons
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initButtonEffects);
} else {
  initButtonEffects();
}

// ============================================
// 8. DROPDOWN MENU - Améliorations
// ============================================

function initDropdowns() {
  // Ajouter des classes pour les animations de dropdown
  const dropdowns = document.querySelectorAll('.nav-item.dropdown');
  
  dropdowns.forEach(dropdown => {
    const menu = dropdown.querySelector('.dropdown-menu');
    if (!menu) return;

    // Animation au survol sur desktop
    if (window.innerWidth >= 992) {
      dropdown.addEventListener('mouseenter', () => {
        menu.classList.add('show');
        dropdown.classList.add('show');
      });

      dropdown.addEventListener('mouseleave', () => {
        menu.classList.remove('show');
        dropdown.classList.remove('show');
      });
    }

    // Animation avec delai pour les items
    const items = menu.querySelectorAll('.dropdown-item');
    items.forEach((item, index) => {
      item.style.animationDelay = `${index * 0.03}s`;
    });
  });
}

// Initialiser les dropdowns
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initDropdowns);
} else {
  initDropdowns();
}

// ============================================
// 9. MENU MOBILE - Fermeture automatique
// ============================================

function initMobileMenu() {
  const navbarTogglers = document.querySelectorAll('.navbar-toggler');
  const navbarCollapse = document.querySelector('.navbar-collapse');
  
  if (!navbarTogglers.length || !navbarCollapse) return;

  navbarTogglers.forEach(toggler => {
    // Fermer le menu si on clique sur un lien
    const navLinks = document.querySelectorAll('.nav-link:not(.dropdown-toggle)');
    navLinks.forEach(link => {
      link.addEventListener('click', () => {
        if (navbarCollapse.classList.contains('show')) {
          toggler.click(); // Simuler un clic pour fermer
        }
      });
    });

    // Fermer le menu si on clique à l'extérieur
    document.addEventListener('click', (e) => {
      if (!navbarCollapse.contains(e.target) && 
          !Array.from(navbarTogglers).some(t => t.contains(e.target))) {
        if (navbarCollapse.classList.contains('show')) {
          toggler.click();
        }
      }
    });
  });
}

// Initialiser le menu mobile
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initMobileMenu);
} else {
  initMobileMenu();
}

// ============================================
// 10. TOOLTIPS CUSTOM
// ============================================

function initTooltips() {
  // Créer des tooltips pour les éléments avec data-tooltip
  const tooltipElements = document.querySelectorAll('[data-tooltip]');
  
  tooltipElements.forEach(el => {
    el.style.position = 'relative';
    
    const tooltip = document.createElement('span');
    tooltip.className = 'custom-tooltip';
    tooltip.textContent = el.dataset.tooltip || '';
    tooltip.style.position = 'absolute';
    tooltip.style.bottom = '100%';
    tooltip.style.left = '50%';
    tooltip.style.transform = 'translateX(-50%)';
    tooltip.style.background = 'rgba(0, 0, 0, 0.9)';
    tooltip.style.color = '#fff';
    tooltip.style.padding = '0.25rem 0.5rem';
    tooltip.style.borderRadius = '4px';
    tooltip.style.fontSize = '0.75rem';
    tooltip.style.whiteSpace = 'nowrap';
    tooltip.style.opacity = '0';
    tooltip.style.visibility = 'hidden';
    tooltip.style.transition = 'opacity 0.2s ease, visibility 0.2s ease';
    tooltip.style.pointerEvents = 'none';
    tooltip.style.zIndex = '1000';
    
    el.appendChild(tooltip);
    
    el.addEventListener('mouseenter', () => {
      tooltip.style.opacity = '1';
      tooltip.style.visibility = 'visible';
    });
    
    el.addEventListener('mouseleave', () => {
      tooltip.style.opacity = '0';
      tooltip.style.visibility = 'hidden';
    });
  });
}

// Initialiser les tooltips
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initTooltips);
} else {
  initTooltips();
}

// ============================================
// 11. EFFET DE TYPING POUR LE TEXTE
// ============================================

function initTypingEffect() {
  const typingElements = document.querySelectorAll('.typing-effect');
  
  typingElements.forEach(el => {
    const text = el.dataset.text || el.textContent;
    const speed = parseInt(el.dataset.speed) || 50; // ms par caractère
    
    el.textContent = '';
    el.style.opacity = '1';
    
    let i = 0;
    const typeWriter = () => {
      if (i < text.length) {
        el.textContent += text.charAt(i);
        i++;
        setTimeout(typeWriter, speed);
      }
    };
    
    // Démarrer l'animation
    typeWriter();
  });
}

// Initialiser l'effet de typing
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initTypingEffect);
} else {
  initTypingEffect();
}

// ============================================
// 12. COMPTEUR ANIMÉ (Pour statistiques)
// ============================================

function initAnimatedCounters() {
  const counters = document.querySelectorAll('.animated-counter');
  
  if (!shouldAnimate) {
    counters.forEach(counter => {
      counter.textContent = counter.dataset.target || '0';
    });
    return;
  }

  // Configuration de l'Intersection Observer pour les compteurs
  const counterOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.5
  };

  const counterCallback = (entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        animateCounter(entry.target);
        observer.unobserve(entry.target);
      }
    });
  };

  const counterObserver = new IntersectionObserver(counterCallback, counterOptions);

  counters.forEach(counter => {
    counterObserver.observe(counter);
  });

  function animateCounter(element) {
    const target = parseInt(element.dataset.target) || 0;
    const duration = parseInt(element.dataset.duration) || 2000; // ms
    const start = parseInt(element.dataset.start) || 0;
    const startTime = performance.now();

    const updateCounter = (currentTime) => {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);
      
      // Easing function (ease-out-cubic)
      const easeOutCubic = 1 - Math.pow(1 - progress, 3);
      const current = start + (target - start) * easeOutCubic;
      
      element.textContent = Math.round(current).toLocaleString();
      
      if (progress < 1) {
        requestAnimationFrame(updateCounter);
      }
    };

    requestAnimationFrame(updateCounter);
  }
}

// Initialiser les compteurs animés
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initAnimatedCounters);
} else {
  initAnimatedCounters();
}

// ============================================
// 13. GESTION DU LOADING
// ============================================

function initLoadingStates() {
  // Masquer les éléments de loading une fois la page chargée
  const loadingElements = document.querySelectorAll('.loading, .is-loading');
  
  window.addEventListener('load', () => {
    loadingElements.forEach(el => {
      el.classList.remove('loading', 'is-loading');
    });
  });
}

// Initialiser les états de loading
initLoadingStates();

// ============================================
// 14. SMOOTH SCROLL POUR LES ANCRES
// ============================================

function initSmoothScroll() {
  const links = document.querySelectorAll('a[href^="#"]');
  
  links.forEach(link => {
    link.addEventListener('click', (e) => {
      const href = link.getAttribute('href');
      if (href === '#') return;

      const target = document.querySelector(href);
      if (!target) return;

      e.preventDefault();
      
      // Calculer la position avec offset (pour la navbar)
      const navbarHeight = document.getElementById('navbar')?.offsetHeight || 0;
      const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;

      window.scrollTo({
        top: targetPosition,
        behavior: 'smooth'
      });

      // Focus sur l'élément cible
      target.setAttribute('tabindex', '-1');
      target.focus();
    });
  });
}

// Initialiser le smooth scroll
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initSmoothScroll);
} else {
  initSmoothScroll();
}

// ============================================
// 15. DÉTECTION DE LA POSITION DE SCROLL
// ============================================

// Fonction utilitaire pour vérifier si un élément est dans le viewport
function isInViewport(element, offset = 0) {
  const rect = element.getBoundingClientRect();
  return (
    rect.top >= -offset &&
    rect.left >= 0 &&
    rect.bottom <= (window.innerHeight + offset) &&
    rect.right <= window.innerWidth
  );
}

// Exposer la fonction pour une utilisation globale
window.isInViewport = isInViewport;

// ============================================
// 16. LAZY LOADING DES IMAGES
// ============================================

function initLazyLoading() {
  if ('IntersectionObserver' in window) {
    const lazyImages = document.querySelectorAll('img[data-src], img[loading="lazy"]');
    
    const imageObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          if (img.dataset.src) {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
          }
          if (img.dataset.srcset) {
            img.srcset = img.dataset.srcset;
            img.removeAttribute('data-srcset');
          }
          imageObserver.unobserve(img);
        }
      });
    }, {
      rootMargin: '50px 0px',
      threshold: 0.01
    });

    lazyImages.forEach(img => {
      imageObserver.observe(img);
    });
  }
}

// Initialiser le lazy loading
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initLazyLoading);
} else {
  initLazyLoading();
}

// ============================================
// 17. FONCTION UTILITAIRE POUR LES ANIMATIONS
// ============================================

// Fonction pour déclencher une animation CSS
function triggerAnimation(element, animationName, callback) {
  if (!shouldAnimate || !element) {
    if (callback) callback();
    return;
  }

  element.style.animation = '';
  
  // Forcer le reflow
  void element.offsetHeight;
  
  element.style.animation = animationName;
  
  // Écouter la fin de l'animation
  element.addEventListener('animationend', function handler() {
    element.removeEventListener('animationend', handler);
    if (callback) callback();
  });
}

// Exposer la fonction globalement
window.triggerAnimation = triggerAnimation;

// ============================================
// 18. INITIALISATION GLOBALE
// ============================================

// Fonction pour initialiser tous les composants
function initAll() {
  disableAnimationsIfNeeded();
  initScrollAnimations();
  initCardAnimations();
  initVideoPlayers();
  initCharts();
  initButtonEffects();
  initDropdowns();
  initMobileMenu();
  initTooltips();
  initTypingEffect();
  initAnimatedCounters();
  initSmoothScroll();
  initLazyLoading();
}

// Initialiser quand le DOM est prêt
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initAll);
} else {
  initAll();
}

// Initialiser aussi quand la page est complètement chargée
window.addEventListener('load', initAll);
