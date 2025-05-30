<?php
namespace B2B\BulkOrdering;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class B2B_Loader
 *
 * Main plugin bootstrapper. Handles loading dependencies and registering hooks.
 *
 * @package B2B\BulkOrdering
 */
class B2B_Loader
{
    /**
     * @var B2B_Assets Handles asset registration and enqueuing.
     */
    private B2B_Assets $assets;

    /**
     * @var B2B_Render Renders the frontend bulk ordering UI.
     */
    private B2B_Render $renderer;

    /**
     * @var B2B_Cart_Handler Handles AJAX-based cart operations.
     */
    private B2B_Cart_Handler $cartHandler;

    /**
     * Initializes all plugin components and hooks.
     *
     * @return void
     */
    public function init(): void
    {
        $this->load_dependencies();
        $this->init_hooks();
    }

    /**
     * Loads all required class files and instantiates main components.
     *
     * @return void
     */
    private function load_dependencies(): void
    {
        require_once B2B_PLUGIN_DIR . 'includes/class-b2b-assets.php';
        require_once B2B_PLUGIN_DIR . 'includes/class-b2b-render.php';
        require_once B2B_PLUGIN_DIR . 'includes/class-b2b-cart-handler.php';
        require_once B2B_PLUGIN_DIR . 'includes/class-b2b-product-query.php';

        $this->assets = new B2B_Assets();
        $this->renderer = new B2B_Render();
        $this->cartHandler = new B2B_Cart_Handler();
    }

    /**
     * Registers WordPress hooks, shortcodes, and AJAX actions.
     *
     * @return void
     */
    private function init_hooks(): void
    {
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', [$this->assets, 'enqueue_assets']);

        // Register shortcode to render the frontend UI
        add_shortcode('b2b_bulk_ordering', [$this->renderer, 'render_bulk_ordering_ui']);

        // Handle AJAX requests (authenticated + unauthenticated users)
        add_action('wp_ajax_b2b_bulk_add_to_cart', [$this->cartHandler, 'handle']);
        add_action('wp_ajax_nopriv_b2b_bulk_add_to_cart', [$this->cartHandler, 'handle']);
    }
}
