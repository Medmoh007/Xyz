/**
 * COMCV Trading - Scripts Support
 */

(function () {
  "use strict";

  // ============================================
  // FORMULAIRE DE CONTACT
  // ============================================
  function initContactForm() {
    const form = document.getElementById("supportForm");
    if (!form) return;

    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const submitBtn = this.querySelector(".submit-btn");
      const originalText = submitBtn.innerHTML;

      submitBtn.innerHTML =
        '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
      submitBtn.disabled = true;

      // Simulation envoi
      setTimeout(() => {
        showNotification(
          "Votre message a été envoyé. Nous vous répondrons dans les 24 heures.",
          "success",
        );
        form.reset();
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      }, 1500);
    });
  }

  // ============================================
  // SUIVI SECTION ACTIVE AU SCROLL
  // ============================================
  function initActiveNavOnScroll() {
    const sections = document.querySelectorAll(".section-block");
    const navLinks = document.querySelectorAll(".support-nav-links a");
    if (!sections.length || !navLinks.length) return;

    function setActiveNav() {
      let current = "";
      sections.forEach((section) => {
        const sectionTop = section.offsetTop;
        if (window.scrollY >= sectionTop - 150) {
          current = section.getAttribute("id");
        }
      });
      navLinks.forEach((link) => {
        link.classList.remove("active");
        if (link.getAttribute("href") === `#${current}`) {
          link.classList.add("active");
        }
      });
    }

    window.addEventListener("scroll", setActiveNav);
    setActiveNav();
  }

  // ============================================
  // ANIMATION SECTIONS
  // ============================================
  function initSectionAnimations() {
    const sections = document.querySelectorAll(".section-block");
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = "1";
            entry.target.style.transform = "translateY(0)";
          }
        });
      },
      { threshold: 0.1 },
    );

    sections.forEach((section) => {
      section.style.opacity = "0";
      section.style.transform = "translateY(20px)";
      section.style.transition = "opacity 0.5s ease, transform 0.5s ease";
      observer.observe(section);
    });
  }

  // ============================================
  // INITIALISATION
  // ============================================
  document.addEventListener("DOMContentLoaded", function () {
    initContactForm();
    initActiveNavOnScroll();
    initSectionAnimations();
  });
})();
