export default function handleFilterProducts($) {
  $("#b2b-filter-form").on("change", "select", function () {
    const selectedCategory = $(this).val();
    console.log("Selected category:", selectedCategory);

    $(".b2b-product").each(function () {
      const categoryData = $(this).data("categories");
      const categories = categoryData?.toString().split(",") || [];

      if (!selectedCategory || categories.includes(selectedCategory)) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
  });
}
