<?php
namespace B2B\BulkOrdering;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class B2B_Assets
 *
 * Handles enqueuing of frontend assets for the B2B Bulk Ordering plugin.
 */
class B2B_Assets
{
    /**
     * Enqueues frontend scripts and styles when the shortcode is present.
     *
     * @return void
     */
    public function enqueue_assets()
    {
        // 🛡 Ensure we're on a single page or post
        if (!is_singular() && !is_page()) {
            return;
        }

        global $post;

        // 🛡 Ensure $post is valid and contains the shortcode
        if (!isset($post) || !is_object($post) || !has_shortcode($post->post_content, 'b2b_bulk_ordering')) {
            return;
        }

        // 🎨 Enqueue CSS for the plugin
        wp_enqueue_style(
            'b2b-bulk-ordering',
            B2B_PLUGIN_URL . 'assets/css/bulk-ordering.css',
            [],
            B2B_PLUGIN_VERSION
        );

        // ⚙️ Enqueue JS script as a module
        wp_enqueue_script(
            'b2b-bulk-ordering',
            B2B_PLUGIN_URL . 'assets/js/bulk-ordering.js',
            ['jquery'], // 👉 Add 'jquery' if needed
            B2B_PLUGIN_VERSION,
            true
        );

        wp_enqueue_script(
            'b2b-qty-handler',
            B2B_PLUGIN_URL . 'assets/js/handlers/handleQuantityButtons.js',
            [],
            B2B_PLUGIN_VERSION,
            true // Load in footer
        );



        // 🛒 Ensure cart fragments are available (WooCommerce AJAX add-to-cart support)
        wp_enqueue_script('wc-cart-fragments');

        // 🧠 Use module type for the main script
        add_filter('script_loader_tag', [__CLASS__, 'set_script_type_module'], 10, 3);

        // 🚀 Get cached variation data or regenerate
        $variation_data = get_transient('b2b_all_variations_data');

        if ($variation_data === false) {
            $variation_data = [];

            $args = [
                'post_type' => 'product',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'fields' => 'ids',
                'tax_query' => WC()->query->get_tax_query(),
            ];

            $product_ids = get_posts($args);

            foreach ($product_ids as $product_id) {
                $product = wc_get_product($product_id);
                if ($product instanceof \WC_Product_Variable) {
                    $variations = $product->get_available_variations();

                    // Optional: strip fields you don't need to reduce size
                    foreach ($variations as &$variation) {
                        unset($variation['image'], $variation['dimensions'], $variation['weight']); // 🧹 Clean heavy data
                    }

                    $variation_data[$product_id] = $variations;
                }
            }

            set_transient('b2b_all_variations_data', $variation_data, HOUR_IN_SECONDS);
        }

        // 🧩 Localize data for frontend access
        wp_localize_script('b2b-bulk-ordering', 'B2BOrderingData', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce_cart' => wp_create_nonce('b2b_bulk_order'),
            'nonce_filter' => wp_create_nonce('b2b_filter_nonce'),
            'variations' => $variation_data,
            'i18n' => [
                'addToCartError' => __('Failed to add product to cart.', 'bulk-ordering'),
                'missingFields' => __('Please select all required variations.', 'bulk-ordering'),
            ]
        ]);
    }

    /**
     * Modifies the script tag to use type="module" for our JS.
     *
     * @param string $tag
     * @param string $handle
     * @param string $src
     * @return string
     */
    public static function set_script_type_module($tag, $handle, $src)
    {
        if ($handle === 'b2b-bulk-ordering') {
            return '<script type="module" src="' . esc_url($src) . '"></script>';
        }
        return $tag;
    }
}
