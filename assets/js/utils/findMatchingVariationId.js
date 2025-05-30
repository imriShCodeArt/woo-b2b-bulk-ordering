export default function findMatchingVariationId(productId, selectedAttributes) {
    const variations = B2BOrderingData.variations?.[productId] || [];
  
    for (const variation of variations) {
      const variationAttrs = variation.attributes;
      let isMatch = true;
  
      for (const key in selectedAttributes) {
        const selVal = selectedAttributes[key]?.toLowerCase().trim();
  
        const matchingKey = Object.keys(variationAttrs).find((varKey) =>
          decodeURIComponent(varKey) === key
        );
  
        if (!matchingKey) {
          isMatch = false;
          break;
        }
  
        const varVal = variationAttrs[matchingKey]?.toLowerCase().trim();
        if (!varVal || selVal !== varVal) {
          isMatch = false;
          break;
        }
      }
  
      if (isMatch) {
        return parseInt(variation.variation_id, 10);
      }
    }
  
    console.warn('No matching variation found for:', productId, selectedAttributes);
    return 0;
  }
  