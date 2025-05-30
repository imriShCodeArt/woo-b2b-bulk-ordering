<?php
/**
 * Variable Product Selects Partial
 * Expects: global $product
 */

if (!defined('ABSPATH')) {
    exit;
}

$product_attributes     = $product->get_attributes();
$variation_attributes   = $product->get_variation_attributes();

foreach ($variation_attributes as $attr_name => $options):
    $attr = $product_attributes[$attr_name] ?? null;
    ?>
    <select class="b2b-variation-select" data-attr="<?php echo esc_attr($attr_name); ?>">
        <option value=""><?php echo esc_html(wc_attribute_label($attr_name)); ?></option>
        <?php foreach ($options as $option): ?>
            <?php
            $value = esc_attr($option);
            $label = $option;

            if ($attr instanceof WC_Product_Attribute && $attr->is_taxonomy()) {
                $term = get_term_by('slug', $option, $attr_name);
                if ($term && !is_wp_error($term)) {
                    $label = $term->name;
                }
            }
            ?>
            <option value="<?php echo $value; ?>"><?php echo esc_html($label); ?></option>
        <?php endforeach; ?>
    </select>
<?php endforeach; ?>
