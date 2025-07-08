(function () {
  if (window.__b2bQtyHandlerAttached) return;
  window.__b2bQtyHandlerAttached = true;

  document.addEventListener("click", function (event) {
    if (event.target.classList.contains("b2b-qty-minus")) {
      const input = event.target
        .closest(".b2b-quantity-control")
        ?.querySelector(".b2b-quantity-input");
      if (input) {
        const val = parseInt(input.value, 10) || 0;
        input.value = Math.max(0, val - 1);
      }
    }

    if (event.target.classList.contains("b2b-qty-plus")) {
      const input = event.target
        .closest(".b2b-quantity-control")
        ?.querySelector(".b2b-quantity-input");
      if (input) {
        const val = parseInt(input.value, 10) || 0;
        input.value = val + 1;
      }
    }
  });
})();
