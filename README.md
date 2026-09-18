# Narongsak Pumpasert (Phoom) — Developer Portfolio

> **"Building reliable software from code to cloud."**  
> Portfolio website for Narongsak Pumpasert — Third-year Software Engineering Student at Nakhon Pathom Rajabhat University (GPA 3.89), Freelance Full-Stack Developer, and DevOps Enthusiast.

---

## 🌟 Overview & Design Philosophy

This portfolio is built from scratch with zero framework dependencies (No React, No Next.js, No Tailwind, No Bootstrap). It embodies the aesthetic of modern engineering product platforms (inspired by Vercel, Linear, Stripe, and Railway):

- **Minimal & Technical Aesthetic:** Obsidian navy background, crisp borders, electric cyan and violet accents, and subtle typography hierarchy.
- **Pure Web Standards:** Semantic HTML5, modular CSS3 custom properties (design tokens), and Vanilla JavaScript (ES6+).
- **100% Offline & Direct File Support:** Works by opening `index.html` directly in any web browser without needing a Node or web server, while remaining fully hostable on GitHub Pages, Vercel, or Netlify.
- **Zero Hallucinated Metrics:** Authentic representation of academic status, real freelance client engineering experience, and hands-on DevOps capabilities.

---

## 📁 Project Structure

```text
profile/
│
├── index.html              # Main semantic HTML5 document (10 structured sections)
├── css/
│   └── style.css           # Design tokens, dark/light themes, responsive layout
├── js/
│   └── script.js           # Modular Vanilla JS (filtering, terminal, theme, navigation)
├── assets/
│   ├── icons/
│   │   └── favicon.svg     # Brand monogram SVG favicon
│   └── images/             # Image asset directory
└── README.md               # Technical documentation & project guide
```

---

## 🚀 Sections Included

1. **Navigation Bar:** Sticky navigation with glassmorphism blur, brand badge `NP.DEV`, section links with active state indicator, dark/light mode toggle, social shortcuts, and mobile hamburger drawer.
2. **Hero Section:** High-impact technical headline, availability status badge, supporting copy, dual CTA buttons, quick social links, and an interactive developer terminal with live tab switching and command line animations.
3. **About Me:** Professional narrative, key academic highlights (GPA 3.89 at Nakhon Pathom Rajabhat University), and 3 core engineering pillar cards (Full-Stack Development, DevOps & Cloud, Problem Solving).
4. **Tech Stack:** Categorized engineering skill cards (Languages, Frontend, Backend, Database, DevOps & Cloud, CI/CD, Observability, Tools) with dedicated category filter tabs and authentic SVG tech icons. Free of fake progress bars.
5. **Work Experience:** Clean vertical timeline showcasing real-world deliverables as a Freelance Full-Stack Developer (Mar 2023 – Present) and Full-Stack Developer Intern at the Electricity Generating Authority of Thailand (EGAT).
6. **Featured Projects:** Project cards showcasing 6 key projects (InterviewX, UniResearch, Shabu System, Fasirung Telehealth, E-Catalog, Smart Attendance) with problem-solved briefs, technology tags, category badges, dynamic category filtering, and "View More Projects" progressive disclosure.
7. **DevOps & Cloud Engineering:** Visual pipeline diagram (`CODE → BUILD → TEST → CONTAINERIZE → DEPLOY → MONITOR`) with architecture cards for Docker, Kubernetes, AWS + Terraform, GitHub Actions, and Prometheus + Grafana.
8. **Education:** Academic profile detailing B.S. in Software Engineering, GPA 3.89, and relevant university coursework.
9. **Contact:** Direct communication hub with mailto trigger, copy-to-clipboard button with toast feedback, and client-side form validation.
10. **Footer:** Clean footer with navigation links, copyright, and technology disclosures.

---

## 🛠️ Interactions & Features

- **Responsive Design:** Fluid layouts optimized for mobile (< 640px), tablet (641px - 1024px), and desktop (> 1024px) viewports.
- **Theme Switcher:** Dark mode (default) and light mode toggle with state persistence via `localStorage` and `prefers-color-scheme` synchronization.
- **Active Navigation Spy:** High-performance section tracking using `IntersectionObserver`.
- **Project Filtering & Pagination:** Instant category filtering (`All`, `Full-Stack`, `AI`, `DevOps`, `Mobile`, `Web`) combined with smooth "View More Projects" expansion.
- **Interactive Terminal:** Switch between `bash` interactive output and `stack.json` configuration view with animated typing loop.
- **Copy Email to Clipboard:** Single-click copy with custom floating toast notification and fallback for non-secure contexts.
- **Contact Form Validation:** Validates required fields, email syntax, and message length before opening the user's default email client via `mailto:` with pre-populated fields.
- **Scroll Reveal Animations:** Subtle upward translations on scroll utilizing `IntersectionObserver`, respecting users' `prefers-reduced-motion` settings.
- **Back to Top:** Floating smooth-scroll button that dynamically appears when scrolling past the hero fold.

---

## ⚡ Performance & Accessibility (a11y)

- **Semantic HTML5:** `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, `<footer>`.
- **Accessible ARIA Standards:** `aria-label`, `aria-expanded`, `aria-controls`, `aria-selected`, `role="tablist"`, and `role="region"`.
- **Visible Focus States:** Enhanced `:focus-visible` keyboard rings for keyboard navigation.
- **Reduced Motion Support:** `@media (prefers-reduced-motion: reduce)` disables animations and enables instant transitions.
- **Zero External Runtime Blocking:** No external JS bundles, heavy fonts, or remote trackers.

---

## 💻 How to Run

1. **Direct Browser Execution:**
   Double-click `index.html` or open it in any modern browser (Chrome, Edge, Firefox, Safari).
   ```text
   file:///D:/COA/profile/index.html
   ```

2. **Local Static Server (Optional):**
   ```bash
   # Using Python 3
   python -m http.server 8000

   # Using Node.js npx
   npx serve .
   ```
   Open `http://localhost:8000` in your browser.

---

## 📄 License & Credits

© 2026 Narongsak Pumpasert (Phoom). All rights reserved.
