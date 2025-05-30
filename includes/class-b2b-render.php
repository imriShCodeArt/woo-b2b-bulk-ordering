<?php

namespace B2B\BulkOrdering;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class B2B_Render
 *
 * Handles rendering the bulk ordering shortcode UI.
 */
class B2B_Render
{
    /**
     * Renders the [b2b_bulk_ordering] shortcode output.
     *
     * @return string Rendered HTML of the bulk ordering UI.
     */
    public function render_bulk_ordering_ui(): string
    {
        ob_start();

        // Allow themes to override this template: your-theme/woo-b2b-bulk-ordering/bulk-ordering-ui.php
        $template = locate_template('woo-b2b-bulk-ordering/bulk-ordering-ui.php');

        if (!$template) {
            $template = B2B_PLUGIN_DIR . 'templates/bulk-ordering-ui.php';
        }

        /**
         * Allow developers to override the final template path programmatically.
         *
         * @param string $template
         */
        $template = apply_filters('b2b_bulk_ordering_template', $template);

        if (file_exists($template)) {
            include $template;
        } else {
            error_log('❌ B2B Bulk Ordering template not found: ' . $template);
            echo esc_html__('Bulk ordering template not found.', 'bulk-ordering');
        }

        return ob_get_clean();
    }
}
