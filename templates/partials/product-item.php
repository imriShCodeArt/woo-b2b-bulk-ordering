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

$product_id = $product->get_id();
$product_name = $product->get_name();
$category_ids = $product->get_category_ids();
$product_image_id = $product->get_image_id();
$product_image_html = $product_image_id ? wp_get_attachment_image($product_image_id, 'thumbnail', false, ['class' => 'b2b-product-image']) : '';
?>

<div class="b2b-product" data-product-id="<?php echo esc_attr($product_id); ?>"
    data-categories="<?php echo esc_attr(implode(',', $category_ids)); ?>">
    <?php echo $product_image_html; ?>

    <span class="b2b-product-title"><?php echo esc_html($product_name); ?></span>

    <?php if ($product->is_type('variable')): ?>
        <div class="b2b-variation-select-wrapper">
            <?php include plugin_dir_path(__FILE__) . 'variable-product-selects.php'; ?>
        </div>
    <?php endif; ?>

    <?php
    $initial_quantity = isset($initial_quantity) ? (int) $initial_quantity : 0;
    ?>

    <div class="b2b-quantity-control" data-product-id="<?php echo esc_attr($product_id); ?>">
        <button type="button" class="b2b-qty-btn b2b-qty-minus" aria-label="Decrease quantity">−</button>

        <input type="number" min="0" step="1" name="quantities[<?php echo esc_attr($product_id); ?>]"
            value="<?php echo esc_attr($initial_quantity); ?>" class="b2b-quantity-input" placeholder="0"
            aria-label="<?php echo esc_attr(sprintf(__('Quantity for %s', 'bulk-ordering'), $product_name)); ?>" />


        <button type="button" class="b2b-qty-btn b2b-qty-plus" aria-label="Increase quantity">+</button>
    </div>
</div>