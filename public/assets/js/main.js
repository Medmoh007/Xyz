/**
 * COMCV Trading - Scripts globaux
 */

(function () {
  "use strict";

  // ============================================
  // NOTIFICATIONS FLASH
  // ============================================
  window.showNotification = function (
    message,
    type = "success",
    duration = 5000,
  ) {
    const notification = document.createElement("div");
    notification.className = `alert alert-${type} position-fixed top-0 end-0 m-3`;
    notification.style.zIndex = "9999";
    notification.style.minWidth = "300px";
    notification.innerHTML = `
            <i class="fas fa-${type === "success" ? "check-circle" : "exclamation-circle"} me-2"></i>
            <span>${message}</span>
            <button type="button" class="btn-close ms-2" style="color: inherit;" onclick="this.parentElement.remove()"></button>
        `;
    document.body.appendChild(notification);

    setTimeout(() => {
      notification.style.animation = "slideOut 0.3s ease";
      setTimeout(() => notification.remove(), 300);
    }, duration);
  };

  // ============================================
  // COPIE DANS LE PRESSE-PAPIER
  // ============================================
  window.copyToClipboard = function (text, buttonElement = null) {
    if (!text) return false;

    navigator.clipboard
      .writeText(text)
      .then(() => {
        if (buttonElement) {
          const originalHTML = buttonElement.innerHTML;
          buttonElement.innerHTML = '<i class="fas fa-check"></i> Copié!';
          buttonElement.classList.add("copied");
          setTimeout(() => {
            buttonElement.innerHTML = originalHTML;
            buttonElement.classList.remove("copied");
          }, 2000);
        }
        showNotification("Adresse copiée dans le presse-papier!", "success");
      })
      .catch(() => {
        // Fallback pour anciens navigateurs
        const textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("copy");
        document.body.removeChild(textArea);
        if (buttonElement) {
          buttonElement.innerHTML = '<i class="fas fa-check"></i> Copié!';
          setTimeout(() => {
            buttonElement.innerHTML = '<i class="fas fa-copy"></i> Copier';
          }, 2000);
        }
        showNotification("Adresse copiée!", "success");
      });
    return true;
  };

  // ============================================
  // ANIMATIONS AU SCROLL
  // ============================================
  function initScrollAnimations() {
    const elements = document.querySelectorAll(".animate-on-scroll");
    if (!elements.length) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("visible");
          }
        });
      },
      { threshold: 0.1 },
    );

    elements.forEach((el) => observer.observe(el));
  }

  // ============================================
  // SMOOTH SCROLL POUR ANCRES
  // ============================================
  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
      anchor.addEventListener("click", function (e) {
        const href = this.getAttribute("href");
        if (href === "#") return;
        e.preventDefault();
        const target = document.querySelector(href);
        if (target) {
          target.scrollIntoView({ behavior: "smooth", block: "start" });
        }
      });
    });
  }

  // ============================================
  // INITIALISATION
  // ============================================
  document.addEventListener("DOMContentLoaded", function () {
    initScrollAnimations();
    initSmoothScroll();
    console.log("Main.js loaded");
  });
})();
