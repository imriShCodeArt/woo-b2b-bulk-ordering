import findMatchingVariationId from "../utils/findMatchingVariationId.js";

export default function handleAddToCart($) {
  $("#b2b-bulk-ordering-form").on("submit", function (e) {
    e.preventDefault();
    const itemsToAdd = [];

    $(".b2b-product").each(function () {
      const $product = $(this);
      const productId = parseInt($product.data("product-id"), 10);
      const quantity =
        parseInt($product.find(".b2b-quantity-input").val(), 10) || 0;
      if (quantity <= 0) return;

      const variationSelects = $product.find(".b2b-variation-select");
      const isVariable = variationSelects.length > 0;
      let attributes = {};
      let variationId = 0;

      if (isVariable) {
        let allSelected = true;
        variationSelects.each(function () {
          const attrName = $(this).data("attr");
          const attrValue = $(this).val();
          if (!attrValue) {
            allSelected = false;
            return false;
          }
          attributes[`attribute_${attrName}`] = attrValue;
        });

        if (!allSelected) {
          alert(B2BOrderingData.i18n.missingFields);
          return false;
        }

        variationId = findMatchingVariationId(productId, attributes);
        if (!variationId) {
          alert(B2BOrderingData.i18n.missingFields);
          return false;
        }
      }

      itemsToAdd.push({
        product_id: productId,
        quantity,
        variation_id: variationId,
        attributes,
      });
    });

    if (itemsToAdd.length === 0) {
      alert(B2BOrderingData.i18n.addToCartError);
      return;
    }

    $.ajax({
      type: "POST",
      url: B2BOrderingData.ajax_url,
      data: {
        action: "b2b_bulk_add_to_cart",
        nonce: B2BOrderingData.nonce_cart,
        items: itemsToAdd,
      },
      success: function (response) {
        console.log('🧪 Success handler hit. Response:', response);
        // alert(response.data?.message || "✅ Added to cart.");
        if (typeof wc_cart_fragments_params !== "undefined") {
          $(document.body).trigger("wc_fragment_refresh");

          // 🛠️ Manual backup fragment update
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

        $(document.body).trigger("added_to_cart", [itemsToAdd]);
      },

      error: function () {
        alert(B2BOrderingData.i18n.addToCartError);
      },
    });
  });
}
