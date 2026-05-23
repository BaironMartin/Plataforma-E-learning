/**
 * ========================================
 * FUNCIONALIDADES FRONTEND MODERNAS
 * Plataforma Educativa 2024
 * ========================================
 * 
 * Características:
 * - Sistema de temas (claro/oscuro) con persistencia
 * - Menú responsive con animaciones
 * - Scroll suave y navbar dinámica
 * - Utilidades de UI
 * ========================================
 */

(function() {
  'use strict';

  // ========================================
  // SISTEMA DE TEMAS
  // ========================================
  const ThemeManager = {
    init() {
      this.loadTheme();
      this.bindEvents();
    },

    loadTheme() {
      const savedTheme = localStorage.getItem('theme') || 'light';
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      const theme = savedTheme === 'dark' || (!savedTheme && prefersDark) ? 'dark' : 'light';
      this.setTheme(theme);
    },

    setTheme(theme) {
      document.documentElement.setAttribute('data-theme', theme);
      localStorage.setItem('theme', theme);
      this.updateToggleButton(theme);
    },

    toggleTheme() {
      const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
      const newTheme = currentTheme === 'light' ? 'dark' : 'light';
      this.setTheme(newTheme);
      
      // Animación de transición
      document.body.style.transition = 'background-color 0.3s, color 0.3s';
      setTimeout(() => {
        document.body.style.transition = '';
      }, 300);
    },

    updateToggleButton(theme) {
      const toggleBtn = document.querySelector('.theme-switch');
      if (toggleBtn) {
        toggleBtn.setAttribute('aria-label', `Cambiar a modo ${theme === 'light' ? 'oscuro' : 'claro'}`);
      }
    },

    bindEvents() {
      // Toggle button click
      document.querySelectorAll('.theme-switch').forEach(btn => {
        btn.addEventListener('click', () => this.toggleTheme());
      });

      // Keyboard shortcut (Alt + T)
      document.addEventListener('keydown', (e) => {
        if (e.altKey && e.key === 't') {
          e.preventDefault();
          this.toggleTheme();
        }
      });
    }
  };

  // ========================================
  // MENÚ RESPONSIVE
  // ========================================
  const MenuManager = {
    init() {
      this.bindEvents();
    },

    bindEvents() {
      const toggle = document.querySelector('.nav-toggle');
      const menu = document.querySelector('.nav-menu');

      if (toggle && menu) {
        toggle.addEventListener('click', () => {
          menu.classList.toggle('active');
          toggle.classList.toggle('active');
          toggle.setAttribute('aria-expanded', menu.classList.contains('active'));
        });

        // Cerrar menú al hacer clic en enlaces
        menu.querySelectorAll('.nav-link').forEach(link => {
          link.addEventListener('click', () => {
            menu.classList.remove('active');
            toggle.classList.remove('active');
          });
        });

        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', (e) => {
          if (!menu.contains(e.target) && !toggle.contains(e.target)) {
            menu.classList.remove('active');
            toggle.classList.remove('active');
          }
        });
      }
    }
  };

  // ========================================
  // NAVBAR DINÁMICA
  // ========================================
  const NavbarManager = {
    init() {
      this.bindEvents();
    },

    bindEvents() {
      const navbar = document.querySelector('.navbar');
      let lastScroll = 0;

      if (navbar) {
        window.addEventListener('scroll', () => {
          const currentScroll = window.pageYOffset;

          // Añadir clase scrolled
          if (currentScroll > 50) {
            navbar.classList.add('scrolled');
          } else {
            navbar.classList.remove('scrolled');
          }

          // Hide/show on scroll (opcional)
          if (currentScroll > lastScroll && currentScroll > 100) {
            navbar.style.transform = 'translateY(-100%)';
          } else {
            navbar.style.transform = 'translateY(0)';
          }

          lastScroll = currentScroll;
        }, { passive: true });
      }
    }
  };

  // ========================================
  // ANIMACIONES AL SCROLL
  // ========================================
  const ScrollAnimations = {
    init() {
      if ('IntersectionObserver' in window) {
        this.setupObserver();
      }
    },

    setupObserver() {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('animate-fade-in-up');
            observer.unobserve(entry.target);
          }
        });
      }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      });

      // Observar elementos con clase animate-on-scroll
      document.querySelectorAll('.animate-on-scroll').forEach(el => {
        el.style.opacity = '0';
        observer.observe(el);
      });
    }
  };

  // ========================================
  // UTILIDADES DE FORMULARIOS
  // ========================================
  const FormUtils = {
    init() {
      this.setupFloatingLabels();
      this.setupValidation();
    },

    setupFloatingLabels() {
      document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
        const wrapper = input.closest('.form-group');
        if (!wrapper) return;

        const label = wrapper.querySelector('.form-label');
        if (!label) return;

        // Estado inicial
        if (input.value) {
          label.classList.add('floating');
        }

        input.addEventListener('focus', () => label.classList.add('floating'));
        input.addEventListener('blur', () => {
          if (!input.value) {
            label.classList.remove('floating');
          }
        });
      });
    },

    setupValidation() {
      document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', (e) => {
          const inputs = form.querySelectorAll('[required]');
          let isValid = true;

          inputs.forEach(input => {
            const errorEl = input.parentElement.querySelector('.form-error');
            
            if (!input.value.trim()) {
              isValid = false;
              input.classList.add('error');
              
              if (errorEl) {
                errorEl.style.display = 'flex';
              } else {
                this.showError(input, 'Este campo es requerido');
              }
            } else {
              input.classList.remove('error');
              if (errorEl) {
                errorEl.style.display = 'none';
              }
            }
          });

          if (!isValid) {
            e.preventDefault();
          }
        });
      });
    },

    showError(input, message) {
      const errorDiv = document.createElement('div');
      errorDiv.className = 'form-error';
      errorDiv.innerHTML = `⚠️ ${message}`;
      input.parentElement.appendChild(errorDiv);
      
      input.classList.add('error');
      input.addEventListener('input', () => {
        errorDiv.remove();
        input.classList.remove('error');
      }, { once: true });
    }
  };

  // ========================================
  // BOTONES CON EFECTOS RIPPLE
  // ========================================
  const RippleEffect = {
    init() {
      this.bindEvents();
    },

    bindEvents() {
      document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
          const rect = btn.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const y = e.clientY - rect.top;

          const ripple = document.createElement('span');
          ripple.className = 'ripple';
          ripple.style.left = `${x}px`;
          ripple.style.top = `${y}px`;

          btn.appendChild(ripple);

          setTimeout(() => ripple.remove(), 600);
        });
      });
    }
  };

  // ========================================
  // SEARCH BOX INTERACTIVA
  // ========================================
  const SearchBox = {
    init() {
      this.bindEvents();
    },

    bindEvents() {
      document.querySelectorAll('.search-box input[type="text"]').forEach(input => {
        const resetBtn = input.nextElementSibling;
        
        if (resetBtn && resetBtn.tagName === 'BUTTON') {
          resetBtn.addEventListener('click', () => {
            input.value = '';
            input.focus();
          });
        }

        // Búsqueda en tiempo real (si existe un contenedor de resultados)
        const resultsContainer = document.querySelector('.search-results');
        if (resultsContainer) {
          input.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            this.filterContent(query, resultsContainer);
          });
        }
      });
    },

    filterContent(query, container) {
      const items = container.querySelectorAll('.product-container, .card, tr');
      
      items.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(query) ? '' : 'none';
      });
    }
  };

  // ========================================
  // TOOLTIPS
  // ========================================
  const Tooltips = {
    init() {
      this.bindEvents();
    },

    bindEvents() {
      document.querySelectorAll('[data-tooltip]').forEach(el => {
        el.addEventListener('mouseenter', (e) => this.show(e));
        el.addEventListener('mouseleave', () => this.hide());
        el.addEventListener('focus', (e) => this.show(e));
        el.addEventListener('blur', () => this.hide());
      });
    },

    show(e) {
      const tooltip = document.createElement('div');
      tooltip.className = 'tooltip';
      tooltip.textContent = e.target.getAttribute('data-tooltip');
      tooltip.style.cssText = `
        position: absolute;
        background: #1a202c;
        color: white;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.875rem;
        z-index: 1000;
        pointer-events: none;
        animation: fadeIn 0.2s ease-out;
      `;
      
      document.body.appendChild(tooltip);
      
      const rect = e.target.getBoundingClientRect();
      tooltip.style.top = `${rect.top - tooltip.offsetHeight - 10}px`;
      tooltip.style.left = `${rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2)}px`;
      
      e.target._tooltip = tooltip;
    },

    hide() {
      const tooltip = document.activeElement?._tooltip;
      if (tooltip) {
        tooltip.remove();
        document.activeElement._tooltip = null;
      }
    }
  };

  // ========================================
  // INICIALIZACIÓN
  // ========================================
  function init() {
    ThemeManager.init();
    MenuManager.init();
    NavbarManager.init();
    ScrollAnimations.init();
    FormUtils.init();
    RippleEffect.init();
    SearchBox.init();
    Tooltips.init();

    console.log('✨ Frontend moderno inicializado correctamente');
  }

  // Ejecutar cuando el DOM esté listo
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // Exponer globalmente para uso manual si es necesario
  window.FrontendUtils = {
    ThemeManager,
    MenuManager,
    NavbarManager,
    ScrollAnimations,
    FormUtils,
    RippleEffect,
    SearchBox,
    Tooltips
  };

})();

// ========================================
// ESTILOS PARA RIPPLE EFFECT
// ========================================
const style = document.createElement('style');
style.textContent = `
  .ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    transform: scale(0);
    animation: ripple-animation 0.6s linear;
    pointer-events: none;
  }

  @keyframes ripple-animation {
    to {
      transform: scale(4);
      opacity: 0;
    }
  }

  .btn {
    position: relative;
    overflow: hidden;
  }

  .form-input.error {
    border-color: #ff4757 !important;
    box-shadow: 0 0 0 3px rgba(255, 71, 87, 0.2) !important;
  }

  .tooltip {
    animation: fadeIn 0.2s ease-out;
  }
`;
document.head.appendChild(style);
