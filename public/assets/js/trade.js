/**
 * COMCV Trading - Scripts Trading
 */

(function () {
  "use strict";

  // ============================================
  // INITIALISATION GRAPHIQUE TRADINGVIEW
  // ============================================
  function initTradingView() {
    if (typeof TradingView === "undefined") {
      console.error("TradingView not loaded");
      return;
    }

    const container = document.getElementById("tradingview_chart");
    if (!container) return;

    new TradingView.widget({
      container_id: "tradingview_chart",
      width: "100%",
      height: 500,
      symbol: "BINANCE:BTCUSDT",
      interval: "5",
      timezone: "Etc/UTC",
      theme: "dark",
      style: "1",
      locale: "fr",
      toolbar_bg: "#181a20",
      enable_publishing: false,
      withdateranges: true,
      hide_side_toolbar: false,
      allow_symbol_change: true,
    });
  }

  // ============================================
  // FORMULAIRE DE TRADE
  // ============================================
  function initTradeForm() {
    const form = document.getElementById("tradeForm");
    if (!form) return;

    const pairSelect = document.getElementById("pair");
    const priceInput = document.getElementById("price");
    const amountInput = document.getElementById("amount");
    const btnBuy = document.getElementById("btnBuy");
    const btnSell = document.getElementById("btnSell");
    const sideInput = document.getElementById("side");
    const displayAmount = document.getElementById("displayAmount");
    const displayPrice = document.getElementById("displayPrice");
    const totalAmount = document.getElementById("totalAmount");
    const currentPriceSpan = document.getElementById("currentPrice");

    // Prix simulés
    const marketPrices = window.marketPrices || {};

    function updateCurrentPrice() {
      const pair = pairSelect.value;
      const price = marketPrices[pair] || 0;
      currentPriceSpan.textContent = "$" + price.toFixed(2);
      priceInput.value = price.toFixed(2);
      updateCalculations();
    }

    function updateCalculations() {
      const amount = parseFloat(amountInput.value) || 0;
      const price = parseFloat(priceInput.value) || 0;
      const total = amount * price;
      displayAmount.textContent = "$" + amount.toFixed(2);
      displayPrice.textContent = "$" + price.toFixed(2);
      totalAmount.textContent = "$" + total.toFixed(2);
    }

    if (btnBuy && btnSell) {
      btnBuy.addEventListener("click", function () {
        btnBuy.classList.add("active");
        btnSell.classList.remove("active");
        sideInput.value = "buy";
      });
      btnSell.addEventListener("click", function () {
        btnSell.classList.add("active");
        btnBuy.classList.remove("active");
        sideInput.value = "sell";
      });
    }

    if (pairSelect) {
      pairSelect.addEventListener("change", updateCurrentPrice);
      priceInput.addEventListener("input", updateCalculations);
      amountInput.addEventListener("input", updateCalculations);
    }

    updateCurrentPrice();
  }

  // ============================================
  // FERMETURE DES POSITIONS
  // ============================================
  function initClosePositions() {
    document.querySelectorAll(".close-position").forEach((btn) => {
      btn.addEventListener("click", function () {
        const pair = this.dataset.pair;
        const amount = this.dataset.amount;
        const marketPrices = window.marketPrices || {};
        const price = marketPrices[pair] || 0;

        if (
          confirm(
            `Fermer la position ${pair} au prix actuel $${price.toFixed(2)} ?`,
          )
        ) {
          const pairSelect = document.getElementById("pair");
          const priceInput = document.getElementById("price");
          const amountInput = document.getElementById("amount");
          const btnSell = document.getElementById("btnSell");

          if (pairSelect) pairSelect.value = pair;
          if (priceInput) priceInput.value = price.toFixed(2);
          if (amountInput) amountInput.value = amount;
          if (btnSell) btnSell.click();

          setTimeout(() => {
            document.getElementById("tradeForm").submit();
          }, 500);
        }
      });
    });
  }

  // ============================================
  // INITIALISATION
  // ============================================
  document.addEventListener("DOMContentLoaded", function () {
    initTradingView();
    initTradeForm();
    initClosePositions();
  });
})();
