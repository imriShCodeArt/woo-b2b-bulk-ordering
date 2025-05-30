<?php
namespace B2B\BulkOrdering;

if (!defined('ABSPATH')) {
    exit;
}

class B2B_Render
{

    /**
     * Renders the [b2b_bulk_ordering] shortcode output.
     *
     * @return string
     */
    public function render_bulk_ordering_ui(): string
    {
        ob_start();

        // Allow themes to override this template from 'your-theme/woo-b2b-bulk-ordering/'
        $template = locate_template('woo-b2b-bulk-ordering/bulk-ordering-ui.php');

        if (!$template) {
            $template = B2B_PLUGIN_DIR . 'templates/bulk-ordering-ui.php';
        }

        if (file_exists($template)) {
            include $template;
        } else {
            echo esc_html__('Bulk ordering template not found.', 'bulk-ordering');
        }

        return ob_get_clean();
    }
}
