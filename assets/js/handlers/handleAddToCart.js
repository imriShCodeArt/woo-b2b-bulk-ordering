/**
 * Handles the bulk add-to-cart form submission.
 * - Collects selected products and their quantities
 * - Validates variable product selections
 * - Sends AJAX request to add items to WooCommerce cart
 * - Refreshes cart fragments and triggers Woo events
 *
 * @param {jQuery} $ - jQuery instance
 */
import findMatchingVariationId from "../utils/findMatchingVariationId.js";
import showToast from "../utils/showToast.js";

export default function handleAddToCart($) {
  /**
   * Delegate increment/decrement button clicks using event delegation
   */
  $(document).on("click", ".b2b-qty-plus", function () {
    const $input = $(this)
      .closest(".b2b-quantity-control")
      .find(".b2b-quantity-input");
    const currentVal = parseInt($input.val(), 10) || 0;
    $input.val(currentVal + 1).trigger("change");
  });

  $(document).on("click", ".b2b-qty-minus", function () {
    const $input = $(this)
      .closest(".b2b-quantity-control")
      .find(".b2b-quantity-input");
    const currentVal = parseInt($input.val(), 10) || 0;
    if (currentVal > 0) {
      $input.val(currentVal - 1).trigger("change");
    }
  });

  /**
   * Intercept form submission
   */
  $("#b2b-bulk-ordering-form").on("submit", function (e) {
    e.preventDefault();

    /** @type {Array<Object>} itemsToAdd - Validated cart payload */
    const itemsToAdd = [];

    // Loop through all products in the UI
    $(".b2b-product").each(function () {
      const $product = $(this);
      const productId = parseInt($product.data("product-id"), 10);
      const quantity =
        parseInt($product.find(".b2b-quantity-input").val(), 10) || 0;

      // Skip if quantity not valid or missing product ID
      if (quantity <= 0 || !productId) return;

      const variationSelects = $product.find(".b2b-variation-select");
      const isVariable = variationSelects.length > 0;
      let attributes = {};
      let variationId = 0;

      if (isVariable) {
        let allSelected = true;

        // Collect all variation dropdowns
        variationSelects.each(function () {
          const attrName = $(this).data("attr");
          const attrValue = $(this).val();

          if (!attrValue) {
            allSelected = false;
            return false; // break loop early
          }

          attributes[`attribute_${attrName}`] = attrValue;
        });

        // Ensure all variation fields are filled
        if (!allSelected) {
          alert(B2BOrderingData.i18n.missingFields);
          return false; // exit .each loop and main handler
        }

        // Match variation ID based on selected attributes
        variationId = findMatchingVariationId(productId, attributes);

        if (!variationId) {
          alert(B2BOrderingData.i18n.missingFields);
          return false;
        }
      }

      // Add validated item to payload
      itemsToAdd.push({
        product_id: productId,
        quantity,
        variation_id: variationId,
        attributes,
      });
    });

    // Stop if nothing is valid to add
    if (itemsToAdd.length === 0) {
      alert(B2BOrderingData.i18n.addToCartError);
      return;
    }

    // Submit bulk items via AJAX
    $.ajax({
      type: "POST",
      url: B2BOrderingData.ajax_url,
      data: {
        action: "b2b_bulk_add_to_cart",
        nonce: B2BOrderingData.nonce_cart,
        items: itemsToAdd,
      },

      /**
       * On successful response
       * @param {Object} response
       */
      success: function (response) {
        console.log("🧪 Add to cart success:", response);

        // ⚠️ Handle error case first
        if (!response?.success) {
          alert(response?.data?.message || B2BOrderingData.i18n.addToCartError);
          return;
        }

        // ✅ Show a toast with the list of added product names
        const productNames = response?.data?.products || [];
        if (productNames.length > 0) {
          const msg = `${
            productNames.length
          } item(s) added: ${productNames.join(", ")}`;
          showToast($, msg, "success");
        } else {
          showToast($, "✅ Items added to cart.", "success");
        }

        // 🛒 Refresh WooCommerce cart fragments
        if (typeof wc_cart_fragments_params !== "undefined") {
          $(document.body).trigger("wc_fragment_refresh");

          // 🔁 Manual backup refresh
          $.get(
            wc_cart_fragments_params.wc_ajax_url
              .toString()
              .replace("%%endpoint%%", "get_refreshed_fragments"),
            function (data) {
              $.each(data.fragments, function (key, value) {
                $(key).replaceWith(value);
              });
            }
          );
        }

        // 🧩 Trigger WooCommerce event
        $(document.body).trigger("added_to_cart", [itemsToAdd]);
      },

      /**
       * On AJAX error/failure
       * @param {JQuery.jqXHR} xhr
       */
      error: function (xhr) {
        console.error("❌ AJAX add_to_cart failed", xhr);
        alert(B2BOrderingData.i18n.addToCartError);
      },
    });
  });
}
