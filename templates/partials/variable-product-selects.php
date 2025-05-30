<?php
/**
 * Variable Product Selects Partial
 * Expects: global $product
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product;

$product_attributes    = $product->get_attributes();
$variation_attributes  = $product->get_variation_attributes();

foreach ($variation_attributes as $attr_name => $options):
    $attr  = $product_attributes[$attr_name] ?? null;
    $label = wc_attribute_label($attr_name);
    ?>
    
    <label for="b2b-<?php echo esc_attr($attr_name . '-' . $product->get_id()); ?>" class="screen-reader-text">
        <?php echo esc_html(sprintf(__('Select %s', 'bulk-ordering'), $label)); ?>
    </label>
    
    <select
        class="b2b-variation-select"
        data-attr="<?php echo esc_attr($attr_name); ?>"
        id="b2b-<?php echo esc_attr($attr_name . '-' . $product->get_id()); ?>"
        aria-label="<?php echo esc_attr(sprintf(__('Select %s', 'bulk-ordering'), $label)); ?>"
    >
        <option value=""><?php echo esc_html($label); ?></option>

        <?php foreach ($options as $option): ?>
            <?php
            $value = esc_attr($option);
            $display = $option;

            if ($attr instanceof WC_Product_Attribute && $attr->is_taxonomy()) {
                $term = get_term_by('slug', $option, $attr_name);
                if ($term && !is_wp_error($term)) {
                    $display = $term->name;
                }
            }
            ?>
            <option value="<?php echo $value; ?>"><?php echo esc_html($display); ?></option>
        <?php endforeach; ?>
    </select>
<?php endforeach; ?>
