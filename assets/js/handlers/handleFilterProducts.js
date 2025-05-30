export default function handleFilterProducts($) {
  // Bind change only to the category dropdown
  $("#b2b-filter-form").on("change", "#product_cat", function () {
    const selectedCategory = $(this).val();
    console.log("Selected category:", selectedCategory);

    $(".b2b-product").each(function () {
      const categoryData = $(this).data("categories");

      // Defensive fallback: handle empty or undefined data
      const categories = categoryData ? categoryData.toString().split(",") : [];

      const isMatch =
        !selectedCategory || categories.includes(selectedCategory.toString());

      $(this).toggle(isMatch);
    });
  });
}
