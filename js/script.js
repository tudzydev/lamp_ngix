/**
 * ============================================================================
 * PORTFOLIO CLIENT SCRIPT (Vanilla JavaScript)
 * Narongsak Pumpasert (Phoom) — Software Engineering Student & Full-Stack Developer
 * 
 * Features:
 * 1. Dark/Light Theme Switcher with LocalStorage & OS Preference sync
 * 2. Sticky Navbar with Blur & Scroll Threshold Detection
 * 3. Responsive Mobile Menu with Focus Management & Esc listener
 * 4. Active Section Spy using IntersectionObserver
 * 5. Smooth Anchor Scrolling with dynamic offset compensation
 * 6. Interactive Projects Filter (All, Full-Stack, AI, DevOps, Mobile, Web)
 * 7. "View More Projects" toggle (3 initial -> reveal remaining -> show less)
 * 8. Skills Category Tabs Filter
 * 9. Interactive Terminal Component (Tabs switching + typing simulation)
 * 10. Copy Email with Toast Feedback & fallback
 * 11. Mailto Form Client Validation & Launcher
 * 12. Scroll Reveal Animations via IntersectionObserver
 * 13. Back to Top Floating Button
 * 14. Auto-updating Copyright Year
 * ============================================================================
 */

'use strict';

document.addEventListener('DOMContentLoaded', () => {
  // Initialize all interactive modules
  initTheme();
  initNavbar();
  initMobileMenu();
  initActiveNavSpy();
  initSmoothScroll();
  initProjects();
  initSkillsFilter();
  initTerminal();
  initCopyEmail();
  initContactForm();
  initScrollReveal();
  initBackToTop();
  initCopyrightYear();
});

/* --------------------------------------------------------------------------
   1. Theme Management (Dark / Light)
   -------------------------------------------------------------------------- */
function initTheme() {
  const themeToggleBtn = document.getElementById('theme-toggle');
  if (!themeToggleBtn) return;

  const storageKey = 'np_portfolio_theme';
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');

  // Determine initial theme: saved in localStorage or OS preference
  const savedTheme = localStorage.getItem(storageKey);
  const currentTheme = savedTheme || (prefersDark.matches ? 'dark' : 'dark'); // default dark as required
  
  document.documentElement.setAttribute('data-theme', currentTheme);

  // Toggle button click handler
  themeToggleBtn.addEventListener('click', () => {
    const activeTheme = document.documentElement.getAttribute('data-theme') || 'dark';
    const newTheme = activeTheme === 'dark' ? 'light' : 'dark';
    
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem(storageKey, newTheme);
  });

  // Listen to OS color scheme changes if user hasn't set an explicit preference
  prefersDark.addEventListener('change', (e) => {
    if (!localStorage.getItem(storageKey)) {
      const systemTheme = e.matches ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', systemTheme);
    }
  });
}

/* --------------------------------------------------------------------------
   2. Sticky Navbar Appearance on Scroll
   -------------------------------------------------------------------------- */
function initNavbar() {
  const navbar = document.getElementById('navbar');
  if (!navbar) return;

  const onScroll = () => {
    if (window.scrollY > 20) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll(); // initial check
}

/* --------------------------------------------------------------------------
   3. Mobile Navigation Menu
   -------------------------------------------------------------------------- */
function initMobileMenu() {
  const mobileToggle = document.getElementById('mobile-toggle');
  const navMenu = document.getElementById('nav-menu');
  if (!mobileToggle || !navMenu) return;

  const toggleMenu = (open) => {
    const shouldOpen = open !== undefined ? open : !navMenu.classList.contains('open');
    navMenu.classList.toggle('open', shouldOpen);
    mobileToggle.setAttribute('aria-expanded', String(shouldOpen));

    // Prevent body scroll when mobile menu is open
    if (shouldOpen && window.innerWidth <= 768) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = '';
    }
  };

  mobileToggle.addEventListener('click', (e) => {
    e.stopPropagation();
    toggleMenu();
  });

  // Close when clicking any nav link
  navMenu.querySelectorAll('.nav-link').forEach((link) => {
    link.addEventListener('click', () => toggleMenu(false));
  });

  // Close when clicking outside
  document.addEventListener('click', (e) => {
    if (navMenu.classList.contains('open') && !navMenu.contains(e.target) && !mobileToggle.contains(e.target)) {
      toggleMenu(false);
    }
  });

  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && navMenu.classList.contains('open')) {
      toggleMenu(false);
      mobileToggle.focus();
    }
  });

  // Reset menu if window resized above mobile breakpoint
  window.addEventListener('resize', () => {
    if (window.innerWidth > 768 && navMenu.classList.contains('open')) {
      toggleMenu(false);
    }
  });
}

/* --------------------------------------------------------------------------
   4. Active Navigation Section Spy
   -------------------------------------------------------------------------- */
function initActiveNavSpy() {
  const sections = document.querySelectorAll('main > section[id]');
  const navLinks = document.querySelectorAll('.nav-menu .nav-link');
  if (!sections.length || !navLinks.length) return;

  const observerOptions = {
    root: null,
    rootMargin: '-20% 0px -60% 0px',
    threshold: 0
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const id = entry.target.getAttribute('id');
        navLinks.forEach((link) => {
          const href = link.getAttribute('href');
          if (href === `#${id}`) {
            link.classList.add('active');
          } else {
            link.classList.remove('active');
          }
        });
      }
    });
  }, observerOptions);

  sections.forEach((section) => observer.observe(section));
}

/* --------------------------------------------------------------------------
   5. Smooth Anchor Scrolling
   -------------------------------------------------------------------------- */
function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href');
      if (targetId === '#' || targetId === '') return;

      const targetElement = document.querySelector(targetId);
      if (!targetElement) return;

      e.preventDefault();
      const navHeight = document.getElementById('navbar')?.offsetHeight || 72;
      const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - navHeight;

      window.scrollTo({
        top: targetPosition,
        behavior: 'smooth'
      });

      // Update URL hash safely without jump
      if (history.pushState) {
        history.pushState(null, '', targetId);
      }
    });
  });
}

/* --------------------------------------------------------------------------
   6. Projects Section: Filtering & "View More" Toggle
   -------------------------------------------------------------------------- */
function initProjects() {
  const filterBtns = document.querySelectorAll('.filter-btn');
  const projectCards = document.querySelectorAll('.projects-grid .project-card');
  const toggleBtn = document.getElementById('btn-toggle-projects');
  
  if (!projectCards.length) return;

  let currentFilter = 'all';
  let isExpanded = false;
  const initialVisibleCount = 3;

  // Function to apply both filter and show/more visibility rules
  const applyProjectVisibility = () => {
    let matchingCards = [];

    // First, filter by category
    projectCards.forEach((card) => {
      const cardCats = (card.getAttribute('data-categories') || '').split(',').map((c) => c.trim().toLowerCase());
      const isMatch = currentFilter === 'all' || cardCats.includes(currentFilter);

      if (isMatch) {
        matchingCards.push(card);
      } else {
        card.style.display = 'none';
      }
    });

    // Handle "View More" pagination on matching cards
    matchingCards.forEach((card, index) => {
      if (currentFilter === 'all') {
        // If "All" is active, apply the initial 3 limit unless expanded
        if (!isExpanded && index >= initialVisibleCount) {
          card.style.display = 'none';
          card.classList.add('project-hidden');
        } else {
          card.style.display = 'flex';
          card.classList.remove('project-hidden');
        }
      } else {
        // When a specific category filter is active, display all matching projects
        card.style.display = 'flex';
        card.classList.remove('project-hidden');
      }
    });

    // Hide or show the "View More" button depending on active filter
    if (toggleBtn) {
      if (currentFilter !== 'all' || matchingCards.length <= initialVisibleCount) {
        toggleBtn.parentElement.style.display = 'none';
      } else {
        toggleBtn.parentElement.style.display = 'flex';
        const span = toggleBtn.querySelector('span');
        if (span) {
          span.textContent = isExpanded ? 'Show Less Projects' : 'View More Projects';
        }
        toggleBtn.setAttribute('aria-expanded', String(isExpanded));
      }
    }
  };

  // Filter button click listener
  filterBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
      filterBtns.forEach((b) => b.classList.remove('active'));
      btn.classList.add('active');
      currentFilter = (btn.getAttribute('data-filter') || 'all').toLowerCase();
      applyProjectVisibility();
    });
  });

  // "View More Projects" button click listener
  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      isExpanded = !isExpanded;
      applyProjectVisibility();

      // If collapsing back to 3, smooth scroll up to projects top
      if (!isExpanded) {
        const projectsSection = document.getElementById('projects');
        if (projectsSection) {
          const navHeight = document.getElementById('navbar')?.offsetHeight || 72;
          const pos = projectsSection.getBoundingClientRect().top + window.scrollY - navHeight;
          window.scrollTo({ top: pos, behavior: 'smooth' });
        }
      }
    });
  }

  // Initial call
  applyProjectVisibility();
}

/* --------------------------------------------------------------------------
   7. Skills Category Tabs Filter
   -------------------------------------------------------------------------- */
function initSkillsFilter() {
  const skillTabs = document.querySelectorAll('.skill-tab-btn');
  const skillCards = document.querySelectorAll('.skill-category-card');

  if (!skillTabs.length || !skillCards.length) return;

  skillTabs.forEach((tab) => {
    tab.addEventListener('click', () => {
      skillTabs.forEach((t) => {
        t.classList.remove('active');
        t.setAttribute('aria-selected', 'false');
      });
      tab.classList.add('active');
      tab.setAttribute('aria-selected', 'true');

      const selectedCategory = (tab.getAttribute('data-category') || 'all').toLowerCase();

      skillCards.forEach((card) => {
        const cardCat = (card.getAttribute('data-cat') || '').toLowerCase();
        if (selectedCategory === 'all' || cardCat === selectedCategory) {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });
}

/* --------------------------------------------------------------------------
   8. Interactive Terminal Component
   -------------------------------------------------------------------------- */
function initTerminal() {
  const tabBtns = document.querySelectorAll('.terminal-tab-btn');
  const tabShell = document.getElementById('terminal-tab-shell');
  const tabConfig = document.getElementById('terminal-tab-config');

  if (!tabBtns.length || !tabShell || !tabConfig) return;

  tabBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
      tabBtns.forEach((b) => b.classList.remove('active'));
      btn.classList.add('active');

      const target = btn.getAttribute('data-tab');
      if (target === 'shell') {
        tabShell.style.display = 'block';
        tabConfig.style.display = 'none';
      } else if (target === 'config') {
        tabShell.style.display = 'none';
        tabConfig.style.display = 'block';
      }
    });
  });

  // Animated typing effect on terminal command line
  const typingElement = document.getElementById('terminal-typing-text');
  if (typingElement) {
    const commands = [
      'deploy --target=production',
      'docker compose up -d',
      'kubectl get pods -n prod',
      'git push origin main'
    ];
    let cmdIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    let pauseCounter = 0;

    const typeLoop = () => {
      // Respect prefers-reduced-motion
      if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        typingElement.textContent = commands[0];
        return;
      }

      const currentCommand = commands[cmdIndex];

      if (!isDeleting) {
        typingElement.textContent = currentCommand.substring(0, charIndex + 1);
        charIndex++;

        if (charIndex === currentCommand.length) {
          isDeleting = true;
          pauseCounter = 25; // pause at completion
        }
      } else {
        if (pauseCounter > 0) {
          pauseCounter--;
        } else {
          typingElement.textContent = currentCommand.substring(0, charIndex - 1);
          charIndex--;

          if (charIndex === 0) {
            isDeleting = false;
            cmdIndex = (cmdIndex + 1) % commands.length;
          }
        }
      }

      const speed = isDeleting ? 40 : 80;
      setTimeout(typeLoop, speed);
    };

    // Initiate typing loop after brief delay
    setTimeout(typeLoop, 1200);
  }
}

/* --------------------------------------------------------------------------
   9. Copy Email & Toast Notification
   -------------------------------------------------------------------------- */
function showToast(message) {
  const toast = document.getElementById('toast');
  const toastMessage = document.getElementById('toast-message');
  if (!toast) return;

  if (toastMessage) {
    toastMessage.textContent = message || 'Copied to clipboard!';
  }

  toast.classList.add('active');
  clearTimeout(toast._timeout);
  toast._timeout = setTimeout(() => {
    toast.classList.remove('active');
  }, 3000);
}

function initCopyEmail() {
  const copyButtons = document.querySelectorAll('.btn-copy-email');
  if (!copyButtons.length) return;

  const defaultEmail = 'narongsak.dev@example.com';

  copyButtons.forEach((button) => {
    button.addEventListener('click', async (e) => {
      e.preventDefault();
      const emailToCopy = button.getAttribute('data-email') || defaultEmail;

      try {
        if (navigator.clipboard && window.isSecureContext) {
          await navigator.clipboard.writeText(emailToCopy);
        } else {
          // Fallback using textarea for insecure context / file:///
          const textarea = document.createElement('textarea');
          textarea.value = emailToCopy;
          textarea.style.position = 'fixed';
          textarea.style.left = '-9999px';
          textarea.style.top = '0';
          document.body.appendChild(textarea);
          textarea.focus();
          textarea.select();
          document.execCommand('copy');
          document.body.removeChild(textarea);
        }
        showToast(`Copied ${emailToCopy} to clipboard!`);
      } catch (err) {
        showToast(`Email: ${emailToCopy}`);
      }
    });
  });
}

/* --------------------------------------------------------------------------
   10. Contact Form Client-Side Validation & Mailto Trigger
   -------------------------------------------------------------------------- */
function initContactForm() {
  const form = document.getElementById('contact-form');
  if (!form) return;

  const recipientEmail = 'narongsak.dev@example.com';

  const validateField = (id, condition) => {
    const group = document.getElementById(`group-${id}`);
    if (!group) return true;

    if (!condition) {
      group.classList.add('has-error');
      return false;
    } else {
      group.classList.remove('has-error');
      return true;
    }
  };

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const nameInput = document.getElementById('contact-name');
    const emailInput = document.getElementById('contact-email');
    const subjectInput = document.getElementById('contact-subject');
    const messageInput = document.getElementById('contact-message');

    const nameVal = nameInput ? nameInput.value.trim() : '';
    const emailVal = emailInput ? emailInput.value.trim() : '';
    const subjectVal = subjectInput ? subjectInput.value.trim() : '';
    const messageVal = messageInput ? messageInput.value.trim() : '';

    // Validation rules
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const isNameValid = validateField('name', nameVal.length > 0);
    const isEmailValid = validateField('email', emailRegex.test(emailVal));
    const isSubjectValid = validateField('subject', subjectVal.length > 0);
    const isMessageValid = validateField('message', messageVal.length >= 10);

    if (isNameValid && isEmailValid && isSubjectValid && isMessageValid) {
      // Build clean mailto URI with prefilled fields
      const mailtoSubject = encodeURIComponent(`[Portfolio Inquiry] ${subjectVal}`);
      const mailtoBody = encodeURIComponent(
        `Hi Narongsak,\n\n${messageVal}\n\n---\nFrom: ${nameVal}\nEmail: ${emailVal}`
      );
      const mailtoUrl = `mailto:${recipientEmail}?subject=${mailtoSubject}&body=${mailtoBody}`;

      showToast('Opening your default email client...');
      
      // Trigger default mail client
      setTimeout(() => {
        window.location.href = mailtoUrl;
      }, 300);
    }
  });

  // Remove error on input change
  ['name', 'email', 'subject', 'message'].forEach((field) => {
    const el = document.getElementById(`contact-${field}`);
    if (el) {
      el.addEventListener('input', () => {
        const group = document.getElementById(`group-${field}`);
        if (group) group.classList.remove('has-error');
      });
    }
  });
}

/* --------------------------------------------------------------------------
   11. Scroll Reveal Animations (IntersectionObserver)
   -------------------------------------------------------------------------- */
function initScrollReveal() {
  // If user prefers reduced motion, show everything immediately
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.querySelectorAll('.reveal-on-scroll').forEach((el) => {
      el.classList.add('revealed');
    });
    return;
  }

  const revealElements = document.querySelectorAll('.reveal-on-scroll');
  if (!revealElements.length) return;

  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('revealed');
        obs.unobserve(entry.target); // Unobserve once revealed
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: '0px 0px -40px 0px'
  });

  revealElements.forEach((el) => observer.observe(el));
}

/* --------------------------------------------------------------------------
   12. Back to Top Floating Button
   -------------------------------------------------------------------------- */
function initBackToTop() {
  const backToTopBtn = document.getElementById('back-to-top');
  if (!backToTopBtn) return;

  const toggleBtnVisibility = () => {
    if (window.scrollY > 350) {
      backToTopBtn.classList.add('visible');
    } else {
      backToTopBtn.classList.remove('visible');
    }
  };

  window.addEventListener('scroll', toggleBtnVisibility, { passive: true });
  toggleBtnVisibility();

  backToTopBtn.addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
}

/* --------------------------------------------------------------------------
   13. Dynamic Copyright Year
   -------------------------------------------------------------------------- */
function initCopyrightYear() {
  const yearElement = document.getElementById('copyright-year');
  if (yearElement) {
    yearElement.textContent = String(new Date().getFullYear());
  }
}
