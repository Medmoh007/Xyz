/**
 * COMCV Trading - Scripts Dashboard
 */

(function () {
  "use strict";

  // ============================================
  // TRADINGVIEW WIDGET
  // ============================================
  let tradingViewWidget = null;
  let currentSymbol = "BTCUSDT";
  let currentInterval = "1";
  let currentPrice = 45000;

  function initTradingView() {
    if (typeof TradingView === "undefined") {
      console.error("TradingView library not loaded");
      return;
    }

    if (tradingViewWidget !== null) {
      try {
        tradingViewWidget.remove();
      } catch (e) {}
    }

    tradingViewWidget = new TradingView.widget({
      container_id: "tradingview_chart",
      width: "100%",
      height: 400,
      symbol: `BINANCE:${currentSymbol}`,
      interval: currentInterval,
      timezone: "Etc/UTC",
      theme: "dark",
      style: "1",
      locale: "en",
      toolbar_bg: "#181a20",
      enable_publishing: false,
      hide_side_toolbar: false,
      allow_symbol_change: true,
      studies: [
        "MACD@tv-basicstudies",
        "RSI@tv-basicstudies",
        "Volume@tv-basicstudies",
      ],
    });
  }

  // ============================================
  // DONNÉES DE MARCHÉ SIMULÉES
  // ============================================
  function getMarketPrice(symbol) {
    const basePrices = {
      BTCUSDT: 45000,
      ETHUSDT: 3000,
      BNBUSDT: 400,
      XRPUSDT: 0.85,
      SOLUSDT: 100,
    };
    const base = basePrices[symbol] || 45000;
    const variation = (Math.random() * 4 - 2) / 100;
    return base * (1 + variation);
  }

  function updatePriceDisplay() {
    const priceField = document.getElementById("tradePriceDisplay");
    const priceHidden = document.getElementById("tradePrice");
    if (priceField) priceField.value = currentPrice.toFixed(2);
    if (priceHidden) priceHidden.value = currentPrice.toFixed(2);
  }

  // ============================================
  // CARNET D'ORDRES SIMULÉ
  // ============================================
  function updateOrderBook() {
    const asksList = document.getElementById("asksList");
    const bidsList = document.getElementById("bidsList");
    const spreadValue = document.getElementById("spreadValue");

    if (!asksList || !bidsList) return;

    const asks = [],
      bids = [];
    // ASKS
    let askPrice = currentPrice * 1.001;
    for (let i = 0; i < 8; i++) {
      const price = askPrice + i * currentPrice * 0.0002;
      const amount = (Math.random() * 5 + 0.5).toFixed(4);
      const total = (price * amount).toFixed(2);
      asks.push({ price, amount, total });
    }
    // BIDS
    let bidPrice = currentPrice * 0.999;
    for (let i = 0; i < 8; i++) {
      const price = bidPrice - i * currentPrice * 0.0002;
      if (price <= 0) break;
      const amount = (Math.random() * 5 + 0.5).toFixed(4);
      const total = (price * amount).toFixed(2);
      bids.push({ price, amount, total });
    }

    asks.sort((a, b) => a.price - b.price);
    bids.sort((a, b) => b.price - a.price);

    asksList.innerHTML = asks
      .map(
        (order) => `
            <div class="order-row ask">
                <div class="order-price">$${order.price.toFixed(2)}</div>
                <div class="order-amount">${parseFloat(order.amount).toFixed(4)}</div>
                <div class="order-total">$${parseFloat(order.total).toFixed(2)}</div>
            </div>
        `,
      )
      .join("");

    bidsList.innerHTML = bids
      .map(
        (order) => `
            <div class="order-row bid">
                <div class="order-price">$${order.price.toFixed(2)}</div>
                <div class="order-amount">${parseFloat(order.amount).toFixed(4)}</div>
                <div class="order-total">$${parseFloat(order.total).toFixed(2)}</div>
            </div>
        `,
      )
      .join("");

    if (asks.length && bids.length) {
      const spread = asks[0].price - bids[0].price;
      const spreadPercent = ((spread / bids[0].price) * 100).toFixed(2);
      spreadValue.textContent = `$${spread.toFixed(2)} (${spreadPercent}%)`;
    }
  }

  // ============================================
  // TRADE RAPIDE - CALCULS
  // ============================================
  function initQuickTrade() {
    const tradeForm = document.getElementById("tradeForm");
    if (!tradeForm) return;

    const amountInput = document.getElementById("tradeAmount");
    const priceInput = document.getElementById("tradePriceInput");
    const displayAmount = document.getElementById("displayAmount");
    const tradeFee = document.getElementById("tradeFee");
    const tradeTotal = document.getElementById("tradeTotal");
    const marketBtn = document.getElementById("orderTypeMarket");
    const limitBtn = document.getElementById("orderTypeLimit");
    const priceGroup = document.getElementById("priceGroup");
    const marketPriceGroup = document.getElementById("marketPriceGroup");
    const submitBtn = document.getElementById("submitTrade");

    let orderType = "market";

    // --- CORRECTION : plus d'optional chaining, compatible ES5 ---
    const balanceEl = document.querySelector(".balance-amount");
    let userBalance =
      parseFloat(
        balanceEl ? balanceEl.textContent.replace(/[^0-9.]/g, "") : "",
      ) || 0;

    function calculateTotal() {
      const amount = parseFloat(amountInput.value) || 0;
      let price;
      if (orderType === "market") {
        price = currentPrice;
      } else {
        // --- CORRECTION : vérification explicite ---
        price = parseFloat(priceInput ? priceInput.value : "") || currentPrice;
      }
      const total = amount * price;
      const fee = total * 0.001;
      if (displayAmount) displayAmount.textContent = `$${total.toFixed(2)}`;
      if (tradeFee) tradeFee.textContent = `$${fee.toFixed(2)}`;
      if (tradeTotal) tradeTotal.textContent = `$${(total + fee).toFixed(2)}`;

      if (submitBtn) {
        if (total + fee > userBalance) {
          submitBtn.disabled = true;
          submitBtn.innerHTML =
            '<i class="fas fa-exclamation-triangle"></i> Solde insuffisant';
        } else {
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<i class="fas fa-bolt"></i> Exécuter';
        }
      }
    }

    if (marketBtn && limitBtn) {
      marketBtn.addEventListener("click", () => {
        marketBtn.classList.add("active");
        limitBtn.classList.remove("active");
        orderType = "market";
        if (priceGroup) priceGroup.style.display = "none";
        if (marketPriceGroup) marketPriceGroup.style.display = "block";
        calculateTotal();
      });
      limitBtn.addEventListener("click", () => {
        limitBtn.classList.add("active");
        marketBtn.classList.remove("active");
        orderType = "limit";
        if (priceGroup) priceGroup.style.display = "block";
        if (marketPriceGroup) marketPriceGroup.style.display = "block";
        calculateTotal();
      });
    }

    if (amountInput) amountInput.addEventListener("input", calculateTotal);
    if (priceInput) priceInput.addEventListener("input", calculateTotal);

    calculateTotal();
  }

  // ============================================
  // GRAPHIQUE PORTEFEUILLE (CHART.JS)
  // ============================================
  function initPortfolioChart() {
    const canvas = document.getElementById("portfolioChart");
    if (!canvas) return;

    const ctx = canvas.getContext("2d");
    const labels = canvas.dataset.labels
      ? JSON.parse(canvas.dataset.labels)
      : [];
    const values = canvas.dataset.values
      ? JSON.parse(canvas.dataset.values)
      : [];

    new Chart(ctx, {
      type: "line",
      data: {
        labels: labels,
        datasets: [
          {
            label: "Valeur du portefeuille",
            data: values,
            borderColor: "#f0b90b",
            backgroundColor: "rgba(240, 185, 11, 0.1)",
            borderWidth: 2,
            fill: true,
            tension: 0.4,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false }, ticks: { color: "#848e9c" } },
          y: {
            position: "right",
            grid: { color: "rgba(255,255,255,0.05)" },
            ticks: { color: "#848e9c", callback: (v) => "$" + v },
          },
        },
      },
    });
  }

  // ============================================
  // INITIALISATION
  // ============================================
  document.addEventListener("DOMContentLoaded", function () {
    // TradingView
    initTradingView();

    // Mise à jour périodique
    setInterval(() => {
      currentPrice = getMarketPrice(currentSymbol);
      updatePriceDisplay();
      updateOrderBook();
    }, 5000);

    // Événements changement symbole
    const tradingSymbol = document.getElementById("tradingSymbol");
    const tradeSymbol = document.getElementById("tradeSymbol");
    if (tradingSymbol) {
      tradingSymbol.addEventListener("change", function () {
        currentSymbol = this.value;
        if (tradeSymbol) tradeSymbol.value = currentSymbol;
        initTradingView();
        currentPrice = getMarketPrice(currentSymbol);
        updatePriceDisplay();
      });
    }
    if (tradeSymbol) {
      tradeSymbol.addEventListener("change", function () {
        currentSymbol = this.value;
        if (tradingSymbol) tradingSymbol.value = currentSymbol;
        initTradingView();
        currentPrice = getMarketPrice(currentSymbol);
        updatePriceDisplay();
      });
    }

    // Timeframe
    document.querySelectorAll(".timeframe-tab").forEach((btn) => {
      btn.addEventListener("click", function () {
        document
          .querySelectorAll(".timeframe-tab")
          .forEach((b) => b.classList.remove("active"));
        this.classList.add("active");
        currentInterval = this.dataset.interval;
        initTradingView();
      });
    });

    initQuickTrade();
    initPortfolioChart();
    updateOrderBook();
  });
})();
