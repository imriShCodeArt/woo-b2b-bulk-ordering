<?php
namespace B2B\BulkOrdering;

if (!defined('ABSPATH')) {
    exit;
}

class B2B_Loader
{

    /**
     * Initializes all plugin components.
     */
    public function init()
    {
        $this->load_dependencies();
        $this->init_hooks();
    }

    /**
     * Load all required files.
     */
    private function load_dependencies()
    {
        require_once B2B_PLUGIN_DIR . 'includes/class-b2b-assets.php';
        require_once B2B_PLUGIN_DIR . 'includes/class-b2b-render.php';
        require_once B2B_PLUGIN_DIR . 'includes/class-b2b-cart-handler.php';
        require_once B2B_PLUGIN_DIR . 'includes/class-b2b-product-query.php';
    }

    /**
     * Register WordPress and WooCommerce hooks.
     */
    private function init_hooks()
    {
        // Enqueue scripts/styles
        add_action('wp_enqueue_scripts', [new B2B_Assets(), 'enqueue_assets']);

        // Register shortcode for frontend UI
        add_shortcode('b2b_bulk_ordering', [new B2B_Render(), 'render_bulk_ordering_ui']);

        // AJAX handlers for add-to-cart
        $cartHandler = new B2B_Cart_Handler();

        add_action('wp_ajax_b2b_bulk_add_to_cart', [$cartHandler, 'handle']);
        add_action('wp_ajax_nopriv_b2b_bulk_add_to_cart', [$cartHandler, 'handle']);
    }
}
