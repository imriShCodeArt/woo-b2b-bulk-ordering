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

    $filter_form = plugin_dir_path(__FILE__) . 'partials/filter-form.php';
    if (file_exists($filter_form)) {
        include $filter_form;
    } else {
        error_log('❌ Missing filter-form partial: ' . $filter_form);
    }
    ?>

    <!-- 🛒 Bulk Add Form -->
    <form id="b2b-bulk-ordering-form" aria-label="<?php esc_attr_e('Bulk Ordering Form', 'bulk-ordering'); ?>">
        <div id="b2b-product-list-wrapper">
            <div class="b2b-product-list">
                <?php
                $all_products = B2B_Product_Query::get_all_products();

                if (!empty($all_products)):
                    foreach ($all_products as $product):
                        if (!$product instanceof \WC_Product) {
                            continue;
                        }

                        $item_template = plugin_dir_path(__FILE__) . 'partials/product-item.php';
                        if (file_exists($item_template)) {
                            include $item_template;
                        } else {
                            error_log('❌ Missing product-item partial: ' . $item_template);
                        }
                    endforeach;
                endif;

                ?>
            </div>
        </div>

        <div class="b2b-submit-fixed-wrapper">
            <button type="submit" class="b2b-submit-button"
                aria-label="<?php esc_attr_e('Add selected products to cart', 'bulk-ordering'); ?>">
                <?php echo esc_html__('🛒 Add Selected to Cart', 'bulk-ordering'); ?>
            </button>
        </div>
    </form>
</div>