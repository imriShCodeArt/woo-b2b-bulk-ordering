import handleAddToCart from "./handlers/handleAddToCart.js";
import handleFilterProducts from "./handlers/handleFilterProducts.js";

jQuery(function ($) {
  try {
    handleAddToCart($);
  } catch (e) {
    console.error("handleAddToCart failed:", e);
  }

  try {
    handleFilterProducts($);
  } catch (e) {
    console.error("handleFilterProducts failed:", e);
  }
});
