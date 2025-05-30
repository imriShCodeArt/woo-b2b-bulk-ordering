<?php
/**
 * Product Item Partial
 * Expects: global $product
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product;

if (!$product instanceof \WC_Product) {
    return;
}

$product_id    = $product->get_id();
$product_name  = $product->get_name();
$category_ids  = $product->get_category_ids();
?>

<div
    class="b2b-product"
    data-product-id="<?php echo esc_attr($product_id); ?>"
    data-categories="<?php echo esc_attr(implode(',', $category_ids)); ?>"
>
    <span class="b2b-product-title"><?php echo esc_html($product_name); ?></span>

    <?php if ($product->is_type('variable')): ?>
        <div class="b2b-variation-select-wrapper">
            <?php include plugin_dir_path(__FILE__) . 'variable-product-selects.php'; ?>
        </div>
    <?php endif; ?>

    <input
        type="number"
        min="0"
        class="b2b-quantity-input"
        placeholder="<?php esc_attr_e('Qty', 'bulk-ordering'); ?>"
        aria-label="<?php echo esc_attr(sprintf(__('Quantity for %s', 'bulk-ordering'), $product_name)); ?>"
    />
</div>
