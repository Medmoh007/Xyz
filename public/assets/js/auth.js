/**
 * COMCV Trading - Scripts authentification (login/register)
 */

(function () {
  "use strict";

  // ============================================
  // VALIDATION FORMULAIRE LOGIN
  // ============================================
  function initLoginForm() {
    const form = document.getElementById("loginForm");
    if (!form) return;

    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const rememberCheckbox = document.getElementById("remember");

    // Focus sur le champ email
    emailInput.focus();

    // Remplissage automatique démo
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get("demo") === "1") {
      emailInput.value = "demo@comcv.com";
      passwordInput.value = "demo123";
    }

    // Restauration email mémorisé
    const savedEmail = localStorage.getItem("rememberedEmail");
    if (savedEmail) {
      emailInput.value = savedEmail;
      if (rememberCheckbox) rememberCheckbox.checked = true;
    }

    // Sauvegarde email lors de la soumission
    form.addEventListener("submit", function () {
      if (rememberCheckbox && rememberCheckbox.checked) {
        localStorage.setItem("rememberedEmail", emailInput.value);
      } else {
        localStorage.removeItem("rememberedEmail");
      }
    });
  }

  // ============================================
  // VALIDATION FORMULAIRE REGISTER
  // ============================================
  function initRegisterForm() {
    const form = document.getElementById("registerForm");
    if (!form) return;

    const passwordInput = document.getElementById("password");
    const passwordConfirm = document.getElementById("password_confirm");
    const strengthFill = document.getElementById("strengthFill");
    const strengthText = document.getElementById("strengthText");
    const passwordMatch = document.getElementById("passwordMatch");

    // Force du mot de passe
    passwordInput.addEventListener("input", function () {
      const pwd = this.value;
      let strength = 0;

      if (pwd.length >= 8) strength++;
      if (pwd.length >= 12) strength++;
      if (/[A-Z]/.test(pwd)) strength++;
      if (/[0-9]/.test(pwd)) strength++;
      if (/[^A-Za-z0-9]/.test(pwd)) strength++;

      let strengthClass = "",
        text = "",
        color = "";
      if (strength <= 2) {
        strengthClass = "weak";
        text = "Faible";
        color = "#f6465d";
      } else if (strength <= 4) {
        strengthClass = "medium";
        text = "Moyen";
        color = "#f0b90b";
      } else {
        strengthClass = "strong";
        text = "Fort";
        color = "#0ecb81";
      }

      if (strengthFill) {
        strengthFill.className = "strength-fill " + strengthClass;
      }
      if (strengthText) {
        strengthText.textContent = "Force : " + text;
        strengthText.style.color = color;
      }
    });

    // Correspondance des mots de passe
    function checkMatch() {
      if (!passwordMatch) return;
      if (passwordConfirm.value === "") {
        passwordMatch.textContent = "";
        return;
      }
      if (passwordInput.value === passwordConfirm.value) {
        passwordMatch.textContent = "✓ Les mots de passe correspondent";
        passwordMatch.style.color = "#0ecb81";
      } else {
        passwordMatch.textContent = "✗ Les mots de passe ne correspondent pas";
        passwordMatch.style.color = "#f6465d";
      }
    }

    passwordInput.addEventListener("input", checkMatch);
    passwordConfirm.addEventListener("input", checkMatch);
  }

  // Initialisation
  document.addEventListener("DOMContentLoaded", function () {
    initLoginForm();
    initRegisterForm();
  });
})();
