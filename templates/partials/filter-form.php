<?php
/**
 * Filter Form Partial
 * Expects: $categories, $tags, $current_cat, $current_tag
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<form method="get" id="b2b-filter-form" class="b2b-filter-form">
    <!-- Category Filter -->
    <label for="product_cat"><?php esc_html_e('Filter by Category:', 'bulk-ordering'); ?></label>
    <select name="product_cat" id="product_cat">
        <option value=""><?php esc_html_e('All Categories', 'bulk-ordering'); ?></option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo esc_attr($cat->term_id); ?>" <?php selected($current_cat, $cat->term_id); ?>>
                <?php echo esc_html($cat->name); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Tag Filter (currently disabled) -->
    <!--
    <label for="product_tag"><?php esc_html_e('Filter by Tag:', 'bulk-ordering'); ?></label>
    <select name="product_tag" id="product_tag">
        <option value=""><?php esc_html_e('All Tags', 'bulk-ordering'); ?></option>
        <?php foreach ($tags as $tag): ?>
            <option value="<?php echo esc_attr($tag->term_id); ?>" <?php selected($current_tag, $tag->term_id); ?>>
                <?php echo esc_html($tag->name); ?>
            </option>
        <?php endforeach; ?>
    </select>
    -->
</form>
