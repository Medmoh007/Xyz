/*document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.getElementById("togglePassword");
  const password = document.getElementById("password");

  if (toggle && password) {
    toggle.addEventListener("click", () => {
      password.type = password.type === "password" ? "text" : "password";
      toggle.querySelector("i").classList.toggle("fa-eye-slash");
    });
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const pass = document.getElementById("password");
  const confirm = document.getElementById("password_confirm");
  const error = document.getElementById("password-error");

  if (!pass || !confirm) return;

  confirm.addEventListener("input", () => {
    if (confirm.value !== pass.value) {
      error.textContent = "Les mots de passe ne correspondent pas";
    } else {
      error.textContent = "";
    }
  });
});
*/

document.addEventListener("DOMContentLoaded", function () {
  // Configuration commune pour les deux pages
  const forms = document.querySelectorAll(".auth-form");

  if (forms.length === 0) return;

  // Initialiser chaque formulaire sur la page
  forms.forEach((form) => {
    initForm(form);
  });

  function initForm(form) {
    // Vérifier si c'est un formulaire de login ou register
    const isRegister = form.querySelector('input[name="name"]') !== null;
    const passwordInput = form.querySelector(
      'input[type="password"][name="password"]',
    );
    const confirmPasswordInput = form.querySelector(
      'input[name="password_confirm"]',
    );

    // Initialiser les boutons d'affichage/masquage des mots de passe
    initPasswordToggles(form);

    // Si c'est un formulaire d'inscription, ajouter les fonctionnalités supplémentaires
    if (isRegister && passwordInput) {
      initRegisterFeatures(form, passwordInput, confirmPasswordInput);
    }

    // Ajouter la validation et la soumission
    initFormValidation(form, isRegister);
  }

  function initPasswordToggles(form) {
    // Trouver tous les champs de mot de passe
    const passwordInputs = form.querySelectorAll('input[type="password"]');

    passwordInputs.forEach((input) => {
      // Vérifier si un bouton toggle existe déjà
      const existingToggle = input.parentNode.querySelector(
        'button[data-toggle="password"]',
      );
      if (existingToggle) return;

      // Créer le bouton toggle
      const toggleBtn = document.createElement("button");
      toggleBtn.type = "button";
      toggleBtn.setAttribute("data-toggle", "password");
      toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
      toggleBtn.style.cssText = `
                position: absolute;
                right: 12px;
                top: ${input.name === "password" ? "34px" : "34px"};
                background: none;
                border: none;
                color: var(--muted);
                cursor: pointer;
                font-size: 14px;
                padding: 0;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
            `;

      toggleBtn.addEventListener("click", function () {
        if (input.type === "password") {
          input.type = "text";
          this.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
          input.type = "password";
          this.innerHTML = '<i class="fas fa-eye"></i>';
        }
      });

      // Ajouter le bouton au champ de mot de passe
      if (input.parentNode.classList.contains("password-field")) {
        input.parentNode.appendChild(toggleBtn);
      } else {
        // Si le parent n'a pas la classe, l'ajouter et ajouter le bouton
        input.parentNode.classList.add("password-field");
        input.style.paddingRight = "40px";
        input.parentNode.appendChild(toggleBtn);
      }
    });
  }

  function initRegisterFeatures(form, passwordInput, confirmPasswordInput) {
    // Ajouter l'indicateur de force du mot de passe
    const strengthHTML = `
            <div class="password-strength">
                <div class="strength-bar">
                    <div class="strength-fill" id="strength-fill"></div>
                </div>
                <span class="strength-text" id="strength-text">Faible</span>
            </div>
        `;

    const strengthContainer = document.createElement("div");
    strengthContainer.innerHTML = strengthHTML;
    passwordInput.parentNode.appendChild(strengthContainer);

    const strengthFill = document.getElementById("strength-fill");
    const strengthText = document.getElementById("strength-text");

    // Ajouter l'indicateur de correspondance des mots de passe
    if (confirmPasswordInput) {
      const matchIndicator = document.createElement("small");
      matchIndicator.className = "password-match";
      matchIndicator.id = "password-match";
      confirmPasswordInput.parentNode.appendChild(matchIndicator);
    }

    // Suivi de la force du mot de passe
    passwordInput.addEventListener("input", function () {
      const password = this.value;
      const strength = calculatePasswordStrength(password);
      updateStrengthDisplay(strength, strengthFill, strengthText);

      if (confirmPasswordInput) {
        checkPasswordMatch(passwordInput, confirmPasswordInput);
      }
    });

    // Suivi de la correspondance des mots de passe
    if (confirmPasswordInput) {
      confirmPasswordInput.addEventListener("input", function () {
        checkPasswordMatch(passwordInput, confirmPasswordInput);
      });
    }
  }

  function calculatePasswordStrength(password) {
    let strength = 0;

    // Longueur
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;

    // Complexité
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;

    return strength;
  }

  function updateStrengthDisplay(strength, strengthFill, strengthText) {
    let width = 0;
    let text = "Faible";
    let colorClass = "";

    if (strength <= 2) {
      width = 33;
      text = "Faible";
    } else if (strength <= 4) {
      width = 66;
      text = "Moyen";
      colorClass = "medium";
    } else {
      width = 100;
      text = "Fort";
      colorClass = "strong";
    }

    strengthFill.style.width = width + "%";
    strengthFill.className = "strength-fill " + colorClass;
    strengthText.textContent = text;
    strengthText.style.color = getComputedStyle(strengthFill).backgroundColor;
  }

  function checkPasswordMatch(passwordInput, confirmPasswordInput) {
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    const matchIndicator = document.getElementById("password-match");

    if (!matchIndicator) return;

    if (confirmPassword.length === 0) {
      matchIndicator.textContent = "";
      matchIndicator.className = "password-match";
      confirmPasswordInput.style.borderColor = "";
      return;
    }

    if (password !== confirmPassword) {
      matchIndicator.textContent = "Les mots de passe ne correspondent pas";
      matchIndicator.className = "password-match error";
      confirmPasswordInput.style.borderColor = "var(--danger)";
    } else {
      matchIndicator.textContent = "Les mots de passe correspondent";
      matchIndicator.className = "password-match success";
      confirmPasswordInput.style.borderColor = "var(--success)";
    }
  }

  function initFormValidation(form, isRegister) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      // Validation de base
      let isValid = true;
      let errorMessage = "";

      // Validation de l'email
      const emailInput = form.querySelector('input[type="email"]');
      if (emailInput) {
        const email = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
          errorMessage = "Veuillez entrer un email valide";
          isValid = false;
        }
      }

      // Validation du mot de passe
      const passwordInput = form.querySelector(
        'input[type="password"][name="password"]',
      );
      if (passwordInput) {
        const password = passwordInput.value;
        if (isRegister && password.length < 8) {
          errorMessage = "Le mot de passe doit contenir au moins 8 caractères";
          isValid = false;
        } else if (!isRegister && password.length < 6) {
          errorMessage = "Le mot de passe doit contenir au moins 6 caractères";
          isValid = false;
        }
      }

      // Validation spécifique à l'inscription
      if (isRegister) {
        const nameInput = form.querySelector('input[name="name"]');
        if (nameInput && nameInput.value.trim().length < 2) {
          errorMessage = "Le nom doit contenir au moins 2 caractères";
          isValid = false;
        }

        const confirmPasswordInput = form.querySelector(
          'input[name="password_confirm"]',
        );
        if (confirmPasswordInput && passwordInput) {
          if (passwordInput.value !== confirmPasswordInput.value) {
            errorMessage = "Les mots de passe ne correspondent pas";
            isValid = false;
          }
        }
      }

      // Afficher les erreurs ou soumettre
      if (!isValid) {
        showFormError(form, errorMessage);
      } else {
        submitForm(form, isRegister);
      }
    });
  }

  function showFormError(form, message) {
    // Supprimer les anciennes erreurs
    const existingErrors = form.querySelectorAll(".error:not(.shake)");
    existingErrors.forEach((error) => error.remove());

    // Créer et afficher la nouvelle erreur
    const errorDiv = document.createElement("div");
    errorDiv.className = "error shake";
    errorDiv.textContent = message;

    // Insérer avant le bouton de soumission
    const submitBtn = form.querySelector(".btn-primary");
    if (submitBtn) {
      form.insertBefore(errorDiv, submitBtn);

      // Auto-suppression après 5 secondes
      setTimeout(() => {
        if (errorDiv.parentNode) {
          errorDiv.remove();
        }
      }, 5000);
    }
  }

  function submitForm(form, isRegister) {
    const submitBtn = form.querySelector(".btn-primary");
    if (!submitBtn) return;

    // État de chargement
    submitBtn.disabled = true;
    const originalText = submitBtn.textContent;
    submitBtn.textContent = isRegister
      ? "Création en cours..."
      : "Connexion...";
    submitBtn.classList.add("loading");

    // Préparer les données du formulaire
    const formData = new FormData(form);

    // Envoyer la requête
    fetch(form.action, {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => {
        // Vérifier si c'est une réponse JSON
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.includes("application/json")) {
          return response.json();
        }
        // Sinon, traiter comme une redirection HTML
        return { success: true, redirect: response.url };
      })
      .then((data) => {
        if (data.success) {
          // Redirection après succès
          window.location.href = data.redirect || "/dashboard";
        } else {
          // Afficher l'erreur
          showFormError(form, data.message || "Une erreur est survenue");
          resetSubmitButton(submitBtn, originalText);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        showFormError(form, "Erreur de connexion au serveur");
        resetSubmitButton(submitBtn, originalText);
      });
  }

  function resetSubmitButton(button, originalText) {
    button.disabled = false;
    button.textContent = originalText;
    button.classList.remove("loading");
  }

  // Ajouter Font Awesome si non présent
  if (!document.querySelector('link[href*="font-awesome"]')) {
    const fontAwesome = document.createElement("link");
    fontAwesome.rel = "stylesheet";
    fontAwesome.href =
      "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css";
    document.head.appendChild(fontAwesome);
  }
});
