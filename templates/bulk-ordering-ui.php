<?php
/**
 * Bulk Ordering UI Template (Modular)
 * Can be overridden by placing a copy in:
 * your-theme/woo-b2b-bulk-ordering/bulk-ordering-ui.php
 */

if (!defined('ABSPATH')) {
    exit;
}

use B2B\BulkOrdering\B2B_Product_Query;
?>

<div id="b2b-bulk-ordering" class="b2b-bulk-ordering-wrapper">

    <!-- 🔍 Filter Form -->
    <?php
    $categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true]);
    $current_cat = isset($_GET['product_cat']) ? sanitize_text_field($_GET['product_cat']) : '';

    include plugin_dir_path(__FILE__) . 'partials/filter-form.php';
    ?>

    <!-- 🛒 Bulk Add Form -->
    <form id="b2b-bulk-ordering-form">
        <div id="b2b-product-list-wrapper">
            <div class="b2b-product-list">
                <?php
                $all_products = B2B_Product_Query::get_all_products();

                if (!empty($all_products)):
                    foreach ($all_products as $product):
                        setup_postdata($product);
                        include plugin_dir_path(__FILE__) . 'partials/product-item.php';
                    endforeach;
                    wp_reset_postdata();
                else:
                    echo '<p>' . esc_html__('No products found.', 'bulk-ordering') . '</p>';
                endif;
                ?>
            </div>
        </div>

        <div class="b2b-submit-fixed-wrapper">
            <button type="submit" class="b2b-submit-button">
                <?php esc_html_e('🛒 Add Selected to Cart', 'bulk-ordering'); ?>
            </button>
        </div>

    </form>
</div>