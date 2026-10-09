/*
 * Fondation Djama — comportement de l'admin (menu latéral, thème, mots de passe).
 * Chargé une seule fois par le layout, après les scripts de la page.
 */
(function () {
  'use strict';

  const root = document.documentElement;
  const mobile = window.matchMedia('(max-width: 991.98px)');

  // localStorage peut être bloqué (navigation privée stricte) : l'admin reste utilisable sans mémorisation
  const store = {
    get(key) { try { return localStorage.getItem(key); } catch (e) { return null; } },
    set(key, value) { try { localStorage.setItem(key, value); } catch (e) { /* ignoré */ } },
  };


  // ──────────────────────────────────
  // MENU LATÉRAL
  // sur ordinateur : réduit aux icônes (choix mémorisé) ; sur mobile : tiroir par-dessus la page
  // ──────────────────────────────────
  const toggles = document.querySelectorAll('[data-adm-toggle]');

  function closeDrawer() {
    root.classList.remove('adm-open');
    toggles.forEach(button => button.setAttribute('aria-expanded', 'false'));
  }

  toggles.forEach(button => {
    button.addEventListener('click', () => {
      if (mobile.matches) {
        const opened = root.classList.toggle('adm-open');
        button.setAttribute('aria-expanded', opened);
      } else {
        const collapsed = root.classList.toggle('adm-collapsed');
        store.set('adm-sidebar', collapsed ? 'collapsed' : 'expanded');
      }
    });
  });

  document.querySelectorAll('[data-adm-close]').forEach(el => el.addEventListener('click', closeDrawer));
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape') closeDrawer();
  });
  mobile.addEventListener('change', closeDrawer);

  // Menu réduit aux icônes : ouvrir un sous-menu redéploie d'abord le menu, sinon il resterait invisible
  document.querySelectorAll('.adm-nav-toggle').forEach(link => {
    link.addEventListener('click', () => {
      if (!mobile.matches && root.classList.contains('adm-collapsed')) {
        root.classList.remove('adm-collapsed');
        store.set('adm-sidebar', 'expanded');
      }
    });
  });

  // La rubrique en cours reste visible même quand le menu est plus haut que l'écran
  const nav = document.getElementById('admNav');
  const active = nav && nav.querySelector('.active');
  if (active) {
    const top = active.getBoundingClientRect().top - nav.getBoundingClientRect().top;
    if (top > nav.clientHeight - 60) nav.scrollTop = top - nav.clientHeight / 2;
  }


  // ──────────────────────────────────
  // SUPPRESSIONS PAR FORMULAIRE (Projets, Réalisations, Messages, Engagements...)
  // Demande une confirmation, supprime sans quitter la page, puis recharge la liste.
  // Sans cela, l'envoi direct affichait la réponse brute du serveur ({"status":200}).
  // ──────────────────────────────────
  function confirmDelete() {
    if (!window.Swal) {
      return Promise.resolve(window.confirm('Etes-vous sûr(e) de vouloir supprimer ? Cette action est irréversible !'));
    }

    return Swal.fire({
      title: 'Etes-vous sûr(e) de vouloir supprimer ?',
      text: 'Cette action est irréversible!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Supprimer!',
      cancelButtonText: 'Annuler',
      customClass: {
        confirmButton: 'btn btn-primary w-xs me-2 mt-2',
        cancelButton: 'btn btn-danger w-xs mt-2',
      },
      buttonsStyling: false,
      showCloseButton: true,
    }).then(result => result.isConfirmed);
  }

  document.addEventListener('submit', event => {
    const form = event.target;
    const method = form.querySelector('input[name="_method"]');
    if (!method || method.value.toUpperCase() !== 'DELETE') return;

    event.preventDefault();
    confirmDelete().then(confirmed => {
      if (!confirmed) return;

      // POST + champ _method=DELETE : même requête que le formulaire d'origine, jeton CSRF compris
      fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
        credentials: 'same-origin',
      }).then(response => {
        if (!response.ok) throw new Error('HTTP ' + response.status);
        window.location.reload();
      }).catch(() => {
        const message = 'La suppression a échoué. Rechargez la page puis réessayez.';
        window.Swal ? Swal.fire({ icon: 'error', title: 'Erreur', text: message }) : window.alert(message);
      });
    });
  });


  // ──────────────────────────────────
  // THÈME CLAIR / SOMBRE (choix mémorisé)
  // ──────────────────────────────────
  document.querySelectorAll('[data-adm-theme]').forEach(button => {
    const icon = button.querySelector('i');
    const paint = () => {
      icon.className = root.getAttribute('data-bs-theme') === 'dark' ? 'ri-sun-line' : 'ri-moon-line';
    };

    button.addEventListener('click', () => {
      const theme = root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
      root.setAttribute('data-bs-theme', theme);
      store.set('adm-theme', theme);
      paint();
    });
    paint();
  });


  // ──────────────────────────────────
  // CHAMPS MOT DE PASSE : bouton « œil » pour afficher ou masquer la saisie
  // ──────────────────────────────────
  document.querySelectorAll('.password-addon').forEach(button => {
    button.addEventListener('click', () => {
      const wrapper = button.closest('.adm-input-icon, .auth-pass-inputgroup, .position-relative') || button.parentElement;
      const input = wrapper.querySelector('input');
      if (!input) return;

      const reveal = input.type === 'password';
      input.type = reveal ? 'text' : 'password';
      const icon = button.querySelector('i');
      if (icon) icon.className = reveal ? 'ri-eye-off-line' : 'ri-eye-line';
    });
  });
})();
