<?php
/**
 * Filter Form Partial
 * Expects: $categories, $tags (optional), $current_cat, $current_tag
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<form method="get" id="b2b-filter-form" class="b2b-filter-form" aria-label="<?php esc_attr_e('Product filter form', 'bulk-ordering'); ?>">
    <fieldset>
        <legend class="screen-reader-text"><?php esc_html_e('Filter products by category', 'bulk-ordering'); ?></legend>

        <!-- Category Filter -->
        <label for="product_cat"><?php esc_html_e('Filter by Category:', 'bulk-ordering'); ?></label>
        <select name="product_cat" id="product_cat" aria-label="<?php esc_attr_e('Select a category', 'bulk-ordering'); ?>">
            <option value=""><?php esc_html_e('All Categories', 'bulk-ordering'); ?></option>
            <?php if (!empty($categories) && is_array($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo esc_attr($cat->term_id); ?>" <?php selected($current_cat, $cat->term_id); ?>>
                        <?php echo esc_html($cat->name); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <!-- Tag Filter (currently disabled) -->
        <!--
        <label for="product_tag"><?php esc_html_e('Filter by Tag:', 'bulk-ordering'); ?></label>
        <select name="product_tag" id="product_tag" aria-label="<?php esc_attr_e('Select a tag', 'bulk-ordering'); ?>">
            <option value=""><?php esc_html_e('All Tags', 'bulk-ordering'); ?></option>
            <?php if (!empty($tags) && is_array($tags)): ?>
                <?php foreach ($tags as $tag): ?>
                    <option value="<?php echo esc_attr($tag->term_id); ?>" <?php selected($current_tag, $tag->term_id); ?>>
                        <?php echo esc_html($tag->name); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
        -->
    </fieldset>
</form>
