/*
 * Fondation Djama — scripts du site public.
 * Chaque bloc s'active seulement si ses éléments sont présents dans la page :
 * une section masquée ou vide en admin ne provoque donc aucune erreur.
 */
(function () {
  'use strict';

  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function debounce(fn, delay = 100) {
    let timer;
    return (...args) => {
      clearTimeout(timer);
      timer = setTimeout(() => fn(...args), delay);
    };
  }

  // sessionStorage peut être bloqué (navigation privée stricte) : on s'en passe alors
  const session = {
    get(key) { try { return sessionStorage.getItem(key); } catch (e) { return null; } },
    set(key, value) { try { sessionStorage.setItem(key, value); } catch (e) { /* ignoré */ } },
  };


  // ──────────────────────────────────
  // ANIMATIONS D'APPARITION AU DÉFILEMENT
  // ──────────────────────────────────
  (function reveal() {
    if (reducedMotion || !('IntersectionObserver' in window)) return;

    // active les animations CSS qui dépendent de JavaScript (barres d'avancement, titres de page)
    document.documentElement.classList.add('js-anim');

    // [variante d'entrée, éléments concernés]
    const targets = [
      ['', '.section-eyebrow, .section-title, .section-lead, .galerie-quote, .galerie-lead, .content-card, .agir-card, ' +
        '.temoignage-card, .prog-list-item, .apropos-info-item, .impact-grid > li, .don-info-block, .contact-list li, ' +
        '.agir-list li, .media-card, .projets-carousel-track, .cta-bande h2, .cta-bande p, .cta-bande .btn, ' +
        '.article-content, .empty-state, .footer-djama .row > *'],
      ['reveal-left', '.apropos-img-block, .programmes-banner, .article-cover'],
      ['reveal-right', '.form-card, .article-aside'],
      ['reveal-zoom', '.gal-item'],
    ];

    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        observer.unobserve(el);
        el.classList.add('is-visible');

        // une fois l'entrée terminée, l'élément retrouve ses propres transitions (survol...)
        setTimeout(() => {
          el.classList.remove('reveal', 'reveal-left', 'reveal-right', 'reveal-zoom', 'is-visible');
          el.style.removeProperty('--reveal-delay');
        }, 900 + parseInt(el.dataset.revealDelay || 0, 10));
      });
    }, { threshold: 0.08, rootMargin: '0px 0px -5% 0px' });

    const rows = new Map(); // éléments d'une même ligne : ils entrent l'un après l'autre

    targets.forEach(([variant, selector]) => {
      document.querySelectorAll(selector).forEach(el => {
        // les cartes d'un carrousel horizontal ou d'une fenêtre ne s'animent pas une à une
        if (el.closest('.modal-overlay, .slide') || (el.closest('.projets-carousel-track') && !el.matches('.projets-carousel-track'))) return;

        // seuls les éléments encore sous l'écran sont masqués : ce qui est déjà visible ne clignote pas
        const top = el.getBoundingClientRect().top;
        if (top < window.innerHeight - 30) return;

        const row = selector.length + ':' + Math.round((top + window.scrollY) / 24);
        const position = rows.get(row) || 0;
        rows.set(row, position + 1);

        const delay = Math.min(position, 5) * 90;
        el.dataset.revealDelay = delay;
        el.style.setProperty('--reveal-delay', delay + 'ms');
        el.classList.add('reveal');
        if (variant) el.classList.add(variant);
        observer.observe(el);
      });
    });

    // barres d'avancement des projets : elles se remplissent à l'entrée dans l'écran
    // (on observe le rail, pas la barre : réduite à zéro, elle n'aurait aucune surface à détecter)
    const bars = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.querySelector('.projet-progress-bar').classList.add('is-filled');
          bars.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    document.querySelectorAll('.projet-progress').forEach(rail => bars.observe(rail));
  })();


  // ──────────────────────────────────
  // INFOS FLASH
  // ──────────────────────────────────
  (function flashBar() {
    const bar = document.getElementById('flashBar');
    if (!bar) return;

    // Masqué par le visiteur : on ne le réaffiche que si les infos ont changé
    if (session.get('flashClosed') === bar.dataset.key) {
      bar.remove();
      return;
    }

    document.getElementById('flashClose').addEventListener('click', () => {
      session.set('flashClosed', bar.dataset.key);
      bar.remove();
    });

    const items = bar.querySelectorAll('.flash-item');
    if (items.length < 2) return;

    const indexLabel = document.getElementById('flashIndex');
    let current = 0;
    let paused = false;

    function show(index) {
      items[current].classList.remove('active');
      current = index;
      items[current].classList.add('active');
      bar.className = 'flash-bar flash-' + items[current].dataset.type;
      if (indexLabel) indexLabel.textContent = current + 1;
    }

    bar.addEventListener('mouseenter', () => paused = true);
    bar.addEventListener('mouseleave', () => paused = false);
    bar.addEventListener('focusin', () => paused = true);
    bar.addEventListener('focusout', () => paused = false);

    setInterval(() => {
      if (!paused && !document.hidden) show((current + 1) % items.length);
    }, 6000);
  })();


  // ──────────────────────────────────
  // NAVBAR
  // ──────────────────────────────────
  (function navbar() {
    const nav = document.getElementById('mainNav');
    if (!nav) return;

    const backTop = document.getElementById('backTop');
    const anchorLinks = Array.from(nav.querySelectorAll('.nav-link-djama[href^="#"]'));
    const sections = anchorLinks
      .map(link => ({ link, section: document.getElementById(link.getAttribute('href').slice(1)) }))
      .filter(entry => entry.section);

    function update() {
      const scrollY = window.scrollY;
      nav.classList.toggle('scrolled', scrollY > 30);
      if (backTop) backTop.classList.toggle('visible', scrollY > 600);

      // Surlignage du lien de la section visible (page d'accueil uniquement)
      if (!sections.length) return;
      let active = anchorLinks[0];
      sections.forEach(({ link, section }) => {
        if (section.getBoundingClientRect().top <= 130) active = link;
      });
      anchorLinks.forEach(link => link.classList.toggle('active', link === active));
    }

    window.addEventListener('scroll', debounce(update, 50), { passive: true });
    update();

    if (backTop) {
      backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: reducedMotion ? 'auto' : 'smooth' }));
    }

    // Sur mobile, le menu se referme après un clic sur un lien
    const menu = document.getElementById('navMenu');
    nav.querySelectorAll('.nav-link-djama, .btn-don').forEach(link => {
      link.addEventListener('click', () => {
        if (menu.classList.contains('show') && window.bootstrap) {
          bootstrap.Collapse.getOrCreateInstance(menu).hide();
        }
      });
    });
  })();


  // ──────────────────────────────────
  // HERO SLIDER
  // ──────────────────────────────────
  (function heroSlider() {
    const slider = document.getElementById('heroSlider');
    if (!slider) return;

    const track = document.getElementById('sliderTrack');
    const slides = slider.querySelectorAll('.slide');
    if (slides.length < 2) return;

    const dots = slider.querySelectorAll('#sliderDots .slider-dot');
    const progress = document.getElementById('sliderProgress');
    const DELAY = 6000;
    let current = 0;
    let timer;

    function show(index) {
      current = (index + slides.length) % slides.length;
      slides.forEach((slide, i) => slide.classList.toggle('active', i === current));
      dots.forEach((dot, i) => dot.classList.toggle('active', i === current));
      track.style.transform = `translateX(-${current * 100}%)`;
      restart();
    }

    function restart() {
      clearTimeout(timer);
      progress.style.transition = 'none';
      progress.style.width = '0%';
      if (reducedMotion) return; // pas de défilement automatique si l'utilisateur limite les animations

      // double rAF : laisse le navigateur appliquer le retour à 0 avant de relancer la barre
      requestAnimationFrame(() => requestAnimationFrame(() => {
        progress.style.transition = `width ${DELAY}ms linear`;
        progress.style.width = '100%';
      }));
      timer = setTimeout(() => show(current + 1), DELAY);
    }

    function pause() {
      clearTimeout(timer);
      progress.style.transition = 'none';
      progress.style.width = '0%';
    }

    document.getElementById('sliderNext').addEventListener('click', () => show(current + 1));
    document.getElementById('sliderPrev').addEventListener('click', () => show(current - 1));
    dots.forEach((dot, i) => dot.addEventListener('click', () => show(i)));

    slider.addEventListener('keydown', e => {
      if (e.key === 'ArrowRight') show(current + 1);
      if (e.key === 'ArrowLeft') show(current - 1);
    });

    let touchStartX = 0;
    slider.addEventListener('touchstart', e => touchStartX = e.touches[0].clientX, { passive: true });
    slider.addEventListener('touchend', e => {
      const delta = touchStartX - e.changedTouches[0].clientX;
      if (Math.abs(delta) > 50) show(current + (delta > 0 ? 1 : -1));
    });

    // Pause pendant la navigation au clavier et quand l'onglet n'est plus visible
    slider.addEventListener('focusin', pause);
    slider.addEventListener('focusout', restart);
    document.addEventListener('visibilitychange', () => document.hidden ? pause() : restart());

    restart();
  })();


  // ──────────────────────────────────
  // COMPTEURS IMPACT
  // ──────────────────────────────────
  (function counters() {
    const counters = document.querySelectorAll('[data-counter]');
    if (!counters.length || reducedMotion || !('IntersectionObserver' in window)) return;

    function animate(el) {
      const target = parseInt(el.dataset.counter, 10);
      const finalText = el.textContent;
      const duration = 1400;
      const start = performance.now();

      function frame(now) {
        const ratio = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - ratio, 3);
        if (ratio < 1) {
          el.textContent = el.dataset.prefix + Math.round(target * eased) + el.dataset.suffix;
          requestAnimationFrame(frame);
        } else {
          el.textContent = finalText; // valeur exacte saisie en admin (espaces, séparateurs...)
        }
      }
      requestAnimationFrame(frame);
    }

    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animate(entry.target);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.6 });

    counters.forEach(counter => observer.observe(counter));
  })();


  // ──────────────────────────────────
  // CAROUSEL PROJETS (défilement natif + flèches)
  // ──────────────────────────────────
  (function projets() {
    const track = document.getElementById('projetsTrack');
    const nav = document.getElementById('projetsNav');
    if (!track || !nav) return;

    const prev = document.getElementById('projetPrev');
    const next = document.getElementById('projetNext');

    function step() {
      const slide = track.querySelector('.projet-slide');
      return slide ? slide.offsetWidth + 24 : track.clientWidth;
    }

    function update() {
      const max = track.scrollWidth - track.clientWidth;
      nav.classList.toggle('is-scrollable', max > 4); // flèches inutiles si tout tient à l'écran
      prev.disabled = track.scrollLeft <= 4;
      next.disabled = track.scrollLeft >= max - 4;
    }

    prev.addEventListener('click', () => track.scrollBy({ left: -step(), behavior: 'smooth' }));
    next.addEventListener('click', () => track.scrollBy({ left: step(), behavior: 'smooth' }));
    track.addEventListener('scroll', debounce(update, 60), { passive: true });
    window.addEventListener('resize', debounce(update));
    update();
  })();


  // ──────────────────────────────────
  // FENÊTRES (don, visionneuse) : ouverture, fermeture, Échap, retour du focus
  // ──────────────────────────────────
  let openedModal = null;
  let lastFocused = null;

  function openModal(modal) {
    lastFocused = document.activeElement;
    openedModal = modal;
    modal.classList.add('active');
    document.body.classList.add('modal-open-djama');
    const close = modal.querySelector('.modal-close');
    if (close) close.focus();
  }

  function closeModal() {
    if (!openedModal) return;
    openedModal.classList.remove('active');
    document.body.classList.remove('modal-open-djama');
    openedModal = null;
    if (lastFocused) lastFocused.focus();
  }

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
  });

  document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', e => {
      if (e.target === modal || e.target.closest('.modal-close')) closeModal();
    });
  });


  // ──────────────────────────────────
  // MODAL AGIR (affichage infos de don)
  // ──────────────────────────────────
  (function agirModal() {
    const modal = document.getElementById('agirModal');
    if (!modal) return;

    const title = document.getElementById('agirModalTitle');
    document.querySelectorAll('.open-agir-modal').forEach(btn => {
      btn.addEventListener('click', () => {
        if (btn.dataset.title) title.textContent = btn.dataset.title;
        openModal(modal);
      });
    });
  })();


  // ──────────────────────────────────
  // COPIE DANS LE PRESSE-PAPIERS (numéros de don, lien de partage)
  // ──────────────────────────────────
  function copyText(text) {
    if (navigator.clipboard && window.isSecureContext) {
      return navigator.clipboard.writeText(text);
    }
    // Repli pour les sites servis en http ou les anciens navigateurs
    return new Promise((resolve, reject) => {
      const field = document.createElement('textarea');
      field.value = text;
      field.style.position = 'fixed';
      field.style.opacity = '0';
      document.body.appendChild(field);
      field.select();
      const done = document.execCommand('copy');
      field.remove();
      done ? resolve() : reject();
    });
  }

  document.addEventListener('click', e => {
    const btn = e.target.closest('[data-copy], [data-copy-link]');
    if (!btn) return;

    copyText(btn.dataset.copy || btn.dataset.copyLink).then(() => {
      const icon = btn.querySelector('i');
      const original = icon.className;
      icon.className = 'bi bi-check2';
      setTimeout(() => icon.className = original, 1500);
    }).catch(() => { /* copie refusée par le navigateur : rien à faire */ });
  });


  // ──────────────────────────────────
  // GALERIE : filtres et visionneuse d'images
  // ──────────────────────────────────
  (function galleryFilters() {
    const buttons = document.querySelectorAll('.media-tab-btn');
    if (!buttons.length) return;

    buttons.forEach(btn => {
      btn.addEventListener('click', () => {
        buttons.forEach(b => {
          b.classList.toggle('active', b === btn);
          b.setAttribute('aria-pressed', b === btn);
        });
        document.querySelectorAll('.media-card').forEach(card => {
          card.hidden = btn.dataset.filter !== 'all' && card.dataset.type !== btn.dataset.filter;
        });
      });
    });
  })();

  (function lightbox() {
    const links = Array.from(document.querySelectorAll('[data-lightbox]'));
    if (!links.length) return;

    const modal = document.createElement('div');
    modal.className = 'modal-overlay lightbox';
    modal.setAttribute('role', 'dialog');
    modal.setAttribute('aria-modal', 'true');
    modal.setAttribute('aria-label', 'Image agrandie');
    modal.innerHTML =
      '<button type="button" class="modal-close" aria-label="Fermer"><i class="bi bi-x-lg" aria-hidden="true"></i></button>' +
      '<button type="button" class="lightbox-nav prev" aria-label="Image précédente"><i class="bi bi-chevron-left" aria-hidden="true"></i></button>' +
      '<figure><img alt=""><figcaption></figcaption></figure>' +
      '<button type="button" class="lightbox-nav next" aria-label="Image suivante"><i class="bi bi-chevron-right" aria-hidden="true"></i></button>';
    document.body.appendChild(modal);

    const image = modal.querySelector('img');
    const caption = modal.querySelector('figcaption');
    let current = 0;

    // Seules les images visibles (non filtrées) sont parcourues
    const visible = () => links.filter(link => !link.closest('[hidden]'));

    function show(index) {
      const list = visible();
      current = (index + list.length) % list.length;
      const link = list[current];
      image.src = link.href;
      image.alt = link.dataset.caption || '';
      caption.textContent = link.dataset.caption || '';
      modal.classList.toggle('single', list.length < 2);
    }

    links.forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        show(visible().indexOf(link));
        openModal(modal);
      });
    });

    modal.addEventListener('click', e => {
      if (e.target.closest('.lightbox-nav.prev')) show(current - 1);
      else if (e.target.closest('.lightbox-nav.next')) show(current + 1);
      else if (!e.target.closest('figure')) closeModal();
    });

    document.addEventListener('keydown', e => {
      if (openedModal !== modal) return;
      if (e.key === 'ArrowRight') show(current + 1);
      if (e.key === 'ArrowLeft') show(current - 1);
    });
  })();


  // ──────────────────────────────────
  // FORMULAIRE D'ENGAGEMENT : le montant ne concerne que les dons
  // ──────────────────────────────────
  (function engagementForm() {
    const type = document.getElementById('engagement-type');
    const amountField = document.getElementById('engagement-amount-field');
    if (!type || !amountField) return;

    const toggle = () => amountField.hidden = type.value !== 'donation';
    type.addEventListener('change', toggle);
    toggle();
  })();
})();
