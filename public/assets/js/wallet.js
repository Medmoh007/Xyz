/**
 * COMCV Trading - Scripts Wallet
 */

(function () {
  "use strict";

  // ============================================
  // COPIE D'ADRESSE
  // ============================================
  function initCopyAddress() {
    const copyBtn = document.getElementById("copyAddressBtn");
    const addressElement = document.querySelector(".wallet-address");
    if (!copyBtn || !addressElement) return;

    copyBtn.addEventListener("click", function () {
      const address = addressElement.textContent.trim();
      if (!address || address === "Non disponible") {
        showNotification("Adresse non disponible", "error");
        return;
      }
      window.copyToClipboard(address, this);
    });
  }

  // ============================================
  // GÉNÉRATION QR CODE
  // ============================================
  function generateQRCode() {
    const qrContainer = document.getElementById("qrContainer");
    const addressElement = document.querySelector(".wallet-address");
    if (!qrContainer || !addressElement) return;

    const address = addressElement.textContent.trim();
    if (address && address !== "Non disponible") {
      const qrSize = 180;
      const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=${qrSize}x${qrSize}&data=${encodeURIComponent(address)}&format=png`;
      qrContainer.innerHTML = `<img src="${qrUrl}" alt="QR Code Wallet" style="width:100%; height:100%; object-fit:contain;">`;
    }
  }

  // ============================================
  // MODAL DE RETRAIT
  // ============================================
  function initWithdrawModal() {
    const withdrawBtn = document.getElementById("withdrawBtn");
    const modal = document.getElementById("withdrawModal");
    if (!modal || !withdrawBtn) return;

    const closeBtn = modal.querySelector(".close-modal");
    const cancelBtn = modal.querySelector(".btn-cancel");
    const maxBtn = document.getElementById("maxAmountBtn");
    const amountInput = document.getElementById("withdrawAmount");
    const networkSelect = document.getElementById("withdrawNetwork");
    const displayAmount = document.getElementById("displayAmount");
    const networkFee = document.getElementById("networkFee");
    const netAmount = document.getElementById("netAmount");

    // Ouvrir
    withdrawBtn.addEventListener("click", () => modal.classList.add("show"));

    // Fermer
    if (closeBtn)
      closeBtn.addEventListener("click", () => modal.classList.remove("show"));
    if (cancelBtn)
      cancelBtn.addEventListener("click", () => modal.classList.remove("show"));
    window.addEventListener("click", (e) => {
      if (e.target === modal) modal.classList.remove("show");
    });

    // Calcul des frais
    function updateWithdrawalCalculations() {
      const amount = parseFloat(amountInput.value) || 0;
      const network = networkSelect.value;
      const fee = network === "TRC20" ? 1 : network === "ERC20" ? 10 : 0.5;
      if (displayAmount) displayAmount.textContent = `$${amount.toFixed(2)}`;
      if (networkFee) networkFee.textContent = `$${fee.toFixed(2)}`;
      if (netAmount)
        netAmount.textContent = `$${Math.max(0, amount - fee).toFixed(2)}`;
    }

    // Max
    if (maxBtn) {
      maxBtn.addEventListener("click", function () {
        const max = parseFloat(amountInput.max);
        if (!isNaN(max)) {
          amountInput.value = max.toFixed(2);
          updateWithdrawalCalculations();
        }
      });
    }

    if (amountInput)
      amountInput.addEventListener("input", updateWithdrawalCalculations);
    if (networkSelect)
      networkSelect.addEventListener("change", updateWithdrawalCalculations);
    updateWithdrawalCalculations();
  }

  // ============================================
  // SÉLECTION RÉSEAUX (page withdrawal)
  // ============================================
  function initNetworkSelection() {
    const networkOptions = document.querySelectorAll(".network-option");
    if (!networkOptions.length) return;

    const networkInput = document.getElementById("network");
    networkOptions.forEach((opt) => {
      opt.addEventListener("click", function () {
        networkOptions.forEach((o) => o.classList.remove("selected"));
        this.classList.add("selected");
        if (networkInput) networkInput.value = this.dataset.network;
        // Déclencher recalcul si présent
        if (typeof updateWithdrawalCalculations === "function")
          updateWithdrawalCalculations();
      });
    });
  }

  // ============================================
  // INITIALISATION
  // ============================================
  document.addEventListener("DOMContentLoaded", function () {
    initCopyAddress();
    generateQRCode();
    initWithdrawModal();
    initNetworkSelection();
  });
})();
