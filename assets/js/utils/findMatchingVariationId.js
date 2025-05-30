/**
 * Finds the matching WooCommerce variation ID based on selected attributes.
 *
 * @param {number} productId - The parent variable product's ID.
 * @param {Object} selectedAttributes - Key/value pairs like { attribute_pa_size: "M" }.
 * @returns {number} The matching variation ID or 0 if not found.
 */
export default function findMatchingVariationId(productId, selectedAttributes) {
  // Get all variations for this product (preloaded from wp_localize_script)
  const variations = B2BOrderingData.variations?.[productId] || [];

  for (const variation of variations) {
    const variationAttrs = variation.attributes;
    let isMatch = true;

    // Compare all selected attributes against this variation's attributes
    for (const key in selectedAttributes) {
      const selVal = selectedAttributes[key]?.toLowerCase().trim();

      // Find matching key (WooCommerce encodes keys sometimes)
      const matchingKey = Object.keys(variationAttrs).find(
        (varKey) => decodeURIComponent(varKey) === key
      );

      // No match for this attribute name
      if (!matchingKey) {
        isMatch = false;
        break;
      }

      // Compare values (case-insensitive, trimmed)
      const varVal = variationAttrs[matchingKey]?.toLowerCase().trim();
      if (!varVal || selVal !== varVal) {
        isMatch = false;
        break;
      }
    }

    // All attributes match? Return this variation
    if (isMatch) {
      return parseInt(variation.variation_id, 10);
    }
  }

  // No match found
  console.warn(
    "No matching variation found for:",
    productId,
    selectedAttributes
  );
  return 0;
}
