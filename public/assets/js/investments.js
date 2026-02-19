/**
 * COMCV Trading - Scripts Investissements
 */

(function () {
  "use strict";

  // ============================================
  // MODAL DE CONFIRMATION
  // ============================================
  function initInvestmentModal() {
    const investButtons = document.querySelectorAll(
      ".btn-invest:not([disabled])",
    );
    const modal = document.getElementById("investmentModal");
    if (!modal) return;

    const closeBtn = modal.querySelector(".close-modal");
    const cancelBtn = modal.querySelector(".btn-cancel");

    function openModal(planCard) {
      const planId = planCard.dataset.planId;
      const planName = planCard.dataset.planName;
      const planAmount = parseFloat(planCard.dataset.planAmount);
      const planRate = parseFloat(planCard.dataset.planRate);
      const planDuration = parseInt(planCard.dataset.planDuration) || 30;

      const dailyProfit = planAmount * (planRate / 100);
      const monthlyProfit = dailyProfit * 30;
      const totalProfit = dailyProfit * planDuration;

      document.getElementById("modalPlanName").textContent = planName;
      document.getElementById("modalPlanAmount").textContent =
        `$${planAmount.toFixed(2)}`;
      document.getElementById("modalPlanRate").textContent = `${planRate}%`;
      document.getElementById("modalPlanDuration").textContent =
        `${planDuration} jours`;
      document.getElementById("modalDailyProfit").textContent =
        `$${dailyProfit.toFixed(2)}`;
      document.getElementById("modalMonthlyProfit").textContent =
        `$${monthlyProfit.toFixed(2)}`;
      document.getElementById("modalTotalProfit").textContent =
        `$${totalProfit.toFixed(2)}`;
      document.getElementById("modalPlanId").value = planId;

      modal.classList.add("show");
    }

    investButtons.forEach((btn) => {
      btn.addEventListener("click", function () {
        const planCard = this.closest(".plan-card");
        if (planCard) openModal(planCard);
      });
    });

    if (closeBtn)
      closeBtn.addEventListener("click", () => modal.classList.remove("show"));
    if (cancelBtn)
      cancelBtn.addEventListener("click", () => modal.classList.remove("show"));
    window.addEventListener("click", (e) => {
      if (e.target === modal) modal.classList.remove("show");
    });
  }

  // ============================================
  // FILTRES D'INVESTISSEMENT
  // ============================================
  function initInvestmentFilters() {
    const filterButtons = document.querySelectorAll(".filter-btn");
    const investmentItems = document.querySelectorAll(".investment-item");

    filterButtons.forEach((btn) => {
      btn.addEventListener("click", function () {
        filterButtons.forEach((b) => b.classList.remove("active"));
        this.classList.add("active");

        const filter = this.dataset.filter;
        investmentItems.forEach((item) => {
          if (filter === "all" || item.dataset.status === filter) {
            item.style.display = "block";
            item.style.opacity = "1";
            item.style.transform = "translateY(0)";
          } else {
            item.style.opacity = "0";
            item.style.transform = "translateY(20px)";
            setTimeout(() => {
              item.style.display = "none";
            }, 300);
          }
        });
      });
    });
  }

  // ============================================
  // ANIMATIONS D'APPARITION
  // ============================================
  function initAnimations() {
    const planCards = document.querySelectorAll(".plan-card");
    planCards.forEach((card, index) => {
      card.style.opacity = "0";
      card.style.transform = "translateY(30px)";
      setTimeout(() => {
        card.style.transition = "all 0.6s ease";
        card.style.opacity = "1";
        card.style.transform = "translateY(0)";
      }, index * 100);
    });
  }

  // ============================================
  // BOUTON SUIVRE (placeholder)
  // ============================================
  function initFollowButtons() {
    document
      .querySelectorAll(".btn-info[data-investment-id]")
      .forEach((btn) => {
        btn.addEventListener("click", function () {
          const id = this.dataset.investmentId;
          showNotification(
            `Fonctionnalité de suivi pour l'investissement #${id} bientôt disponible`,
            "info",
          );
        });
      });
  }

  // ============================================
  // INITIALISATION
  // ============================================
  document.addEventListener("DOMContentLoaded", function () {
    initInvestmentModal();
    initInvestmentFilters();
    initAnimations();
    initFollowButtons();
  });
})();
