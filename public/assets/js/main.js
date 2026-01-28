// Main JavaScript File
document.addEventListener("DOMContentLoaded", function () {
  // Smooth Scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      const href = this.getAttribute("href");
      if (href === "#") return;

      e.preventDefault();
      const target = document.querySelector(href);
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  });

  // Initialize tooltips
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]'),
  );
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Initialize popovers
  const popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]'),
  );
  popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });

  // Form validation enhancement
  const forms = document.querySelectorAll(".needs-validation");
  forms.forEach((form) => {
    form.addEventListener(
      "submit",
      (event) => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add("was-validated");
      },
      false,
    );
  });

  // Auto-hide alerts after 5 seconds
  const alerts = document.querySelectorAll(".alert:not(.alert-permanent)");
  alerts.forEach((alert) => {
    setTimeout(() => {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    }, 5000);
  });

  // Password toggle functionality
  const togglePasswordButtons = document.querySelectorAll(
    "[data-toggle-password]",
  );
  togglePasswordButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const targetId = this.getAttribute("data-target");
      const targetInput = document.querySelector(targetId);

      if (targetInput) {
        const type =
          targetInput.getAttribute("type") === "password" ? "text" : "password";
        targetInput.setAttribute("type", type);

        // Toggle icon
        const icon = this.querySelector("i");
        if (icon) {
          icon.classList.toggle("fa-eye");
          icon.classList.toggle("fa-eye-slash");
        }
      }
    });
  });

  // Number formatting
  window.formatNumber = function (num) {
    return new Intl.NumberFormat("fr-FR", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }).format(num);
  };

  // Currency formatting
  window.formatCurrency = function (amount, currency = "€") {
    return formatNumber(amount) + " " + currency;
  };

  // Date formatting
  window.formatDate = function (dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString("fr-FR", {
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  };

  // Copy to clipboard
  window.copyToClipboard = function (text) {
    navigator.clipboard
      .writeText(text)
      .then(() => {
        // Show success notification
        const notification = document.createElement("div");
        notification.className =
          "alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3";
        notification.style.zIndex = "9999";
        notification.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                Copié dans le presse-papier
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
        document.body.appendChild(notification);

        setTimeout(() => {
          notification.remove();
        }, 3000);
      })
      .catch((err) => {
        console.error("Erreur lors de la copie:", err);
      });
  };

  // Initialize animations on scroll
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("fade-in");
        }
      });
    },
    {
      threshold: 0.1,
      rootMargin: "0px 0px -50px 0px",
    },
  );

  // Observe elements with animation classes
  document.querySelectorAll(".slide-up, .fade-in").forEach((el) => {
    observer.observe(el);
  });

  // Mobile menu enhancement
  const navbarToggler = document.querySelector(".navbar-toggler");
  if (navbarToggler) {
    navbarToggler.addEventListener("click", function () {
      this.classList.toggle("active");
    });
  }

  // Handle dropdown hover on desktop
  if (window.innerWidth > 768) {
    const dropdowns = document.querySelectorAll(".dropdown");
    dropdowns.forEach((dropdown) => {
      dropdown.addEventListener("mouseenter", function () {
        this.querySelector(".dropdown-toggle").click();
      });
      dropdown.addEventListener("mouseleave", function () {
        this.querySelector(".dropdown-toggle").click();
      });
    });
  }

  // Theme switcher (if implemented)
  const themeSwitcher = document.getElementById("themeSwitcher");
  if (themeSwitcher) {
    themeSwitcher.addEventListener("click", function () {
      document.body.classList.toggle("light-theme");
      document.body.classList.toggle("dark-theme");

      // Save preference
      const isDark = document.body.classList.contains("dark-theme");
      localStorage.setItem("theme", isDark ? "dark" : "light");
    });
  }

  // Load saved theme
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme === "light") {
    document.body.classList.remove("dark-theme");
    document.body.classList.add("light-theme");
  }

  // Handle modals
  const modals = document.querySelectorAll(".modal");
  modals.forEach((modal) => {
    modal.addEventListener("shown.bs.modal", function () {
      const input = this.querySelector("input");
      if (input) input.focus();
    });
  });

  // Form input auto-formatting
  document.querySelectorAll('[data-format="currency"]').forEach((input) => {
    input.addEventListener("blur", function () {
      const value = parseFloat(this.value);
      if (!isNaN(value)) {
        this.value = formatCurrency(value);
      }
    });

    input.addEventListener("focus", function () {
      this.value = this.value.replace(/[^\d.]/g, "");
    });
  });

  // Initialize all components
  initComponents();
});

// Component initialization
function initComponents() {
  // Load saved form data
  loadFormData();

  // Initialize charts if Chart.js is loaded
  if (typeof Chart !== "undefined") {
    initCharts();
  }

  // Initialize ApexCharts if loaded
  if (typeof ApexCharts !== "undefined") {
    initApexCharts();
  }
}

// Form data persistence
function loadFormData() {
  document.querySelectorAll("input, textarea, select").forEach((element) => {
    const key = element.name || element.id;
    if (key) {
      const savedValue = localStorage.getItem(`form_${key}`);
      if (savedValue !== null) {
        element.value = savedValue;
      }

      element.addEventListener("input", function () {
        localStorage.setItem(`form_${key}`, this.value);
      });
    }
  });
}

// Clear form data
window.clearFormData = function () {
  const keys = Object.keys(localStorage);
  keys.forEach((key) => {
    if (key.startsWith("form_")) {
      localStorage.removeItem(key);
    }
  });

  document.querySelectorAll("input, textarea, select").forEach((element) => {
    element.value = "";
  });
};

// Toast notification system
window.showToast = function (message, type = "success") {
  const toastContainer =
    document.getElementById("toast-container") || createToastContainer();

  const toastId = "toast-" + Date.now();
  const toast = document.createElement("div");
  toast.id = toastId;
  toast.className = `toast align-items-center text-bg-${type} border-0`;
  toast.setAttribute("role", "alert");
  toast.setAttribute("aria-live", "assertive");
  toast.setAttribute("aria-atomic", "true");

  toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${type === "success" ? "check-circle" : "exclamation-triangle"} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

  toastContainer.appendChild(toast);

  const bsToast = new bootstrap.Toast(toast, {
    autohide: true,
    delay: 5000,
  });

  bsToast.show();

  // Remove toast from DOM after it's hidden
  toast.addEventListener("hidden.bs.toast", () => {
    toast.remove();
  });
};

function createToastContainer() {
  const container = document.createElement("div");
  container.id = "toast-container";
  container.className = "toast-container position-fixed top-0 end-0 p-3";
  container.style.zIndex = "9999";
  document.body.appendChild(container);
  return container;
}

// Confirmation dialog
window.showConfirmation = function (message, callback) {
  const modal = document.getElementById("confirmationModal");
  if (!modal) {
    createConfirmationModal();
    return showConfirmation(message, callback);
  }

  const modalBody = modal.querySelector(".modal-body");
  modalBody.textContent = message;

  const confirmBtn = modal.querySelector(".btn-confirm");
  confirmBtn.onclick = () => {
    if (typeof callback === "function") {
      callback();
    }
    bootstrap.Modal.getInstance(modal).hide();
  };

  const bsModal = new bootstrap.Modal(modal);
  bsModal.show();
};

function createConfirmationModal() {
  const modal = document.createElement("div");
  modal.id = "confirmationModal";
  modal.className = "modal fade";
  modal.setAttribute("tabindex", "-1");
  modal.setAttribute("aria-hidden", "true");

  modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary btn-confirm">Confirmer</button>
                </div>
            </div>
        </div>
    `;

  document.body.appendChild(modal);
}

// Loading overlay
window.showLoading = function (message = "Chargement...") {
  let overlay = document.getElementById("loadingOverlay");
  if (!overlay) {
    overlay = document.createElement("div");
    overlay.id = "loadingOverlay";
    overlay.className = "loading-overlay";
    overlay.innerHTML = `
            <div class="loading-content">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <div class="loading-text mt-3">${message}</div>
            </div>
        `;
    document.body.appendChild(overlay);
  }
  overlay.style.display = "flex";
};

window.hideLoading = function () {
  const overlay = document.getElementById("loadingOverlay");
  if (overlay) {
    overlay.style.display = "none";
  }
};

// Add loading overlay styles
const loadingStyles = document.createElement("style");
loadingStyles.textContent = `
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(10, 14, 23, 0.9);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
    }
    
    .loading-content {
        text-align: center;
        color: var(--text-primary);
    }
    
    .loading-content .spinner-border {
        width: 3rem;
        height: 3rem;
    }
    
    .loading-text {
        font-size: 1.1rem;
        color: var(--text-secondary);
    }
`;
document.head.appendChild(loadingStyles);

// Particles.js Configuration
document.addEventListener("DOMContentLoaded", function () {
  if (typeof particlesJS !== "undefined") {
    particlesJS("particles-js", {
      particles: {
        number: {
          value: 80,
          density: {
            enable: true,
            value_area: 800,
          },
        },
        color: {
          value: ["#00d4ff", "#7928ca", "#ff0080"],
        },
        shape: {
          type: "circle",
        },
        opacity: {
          value: 0.5,
          random: true,
        },
        size: {
          value: 3,
          random: true,
        },
        line_linked: {
          enable: true,
          distance: 150,
          color: "#00d4ff",
          opacity: 0.2,
          width: 1,
        },
        move: {
          enable: true,
          speed: 2,
          direction: "none",
          random: true,
          straight: false,
          out_mode: "out",
          bounce: false,
        },
      },
      interactivity: {
        detect_on: "canvas",
        events: {
          onhover: {
            enable: true,
            mode: "grab",
          },
          onclick: {
            enable: true,
            mode: "push",
          },
        },
      },
      retina_detect: true,
    });
  }

  // Custom particles interaction
  const canvas = document.querySelector("#particles-js canvas");
  if (canvas) {
    canvas.style.cursor = "grab";

    canvas.addEventListener("mousedown", () => {
      canvas.style.cursor = "grabbing";
    });

    canvas.addEventListener("mouseup", () => {
      canvas.style.cursor = "grab";
    });
  }
});
