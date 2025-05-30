<?php
namespace B2B\BulkOrdering;

if (!defined('ABSPATH')) {
    exit;
}

class B2B_Assets
{
    /**
     * Enqueue plugin frontend assets.
     */
    public function enqueue_assets()
    {
        // Only enqueue on pages where shortcode exists (optional optimization)
        if (!is_singular() && !is_page()) {
            return;
        }

        global $post;
        if (!has_shortcode($post->post_content, 'b2b_bulk_ordering')) {
            return;
        }

        // Enqueue CSS
        wp_enqueue_style(
            'b2b-bulk-ordering',
            B2B_PLUGIN_URL . 'assets/css/bulk-ordering.css',
            [],
            B2B_PLUGIN_VERSION
        );

        // Enqueue JS
        wp_enqueue_script(
            'b2b-bulk-ordering',
            B2B_PLUGIN_URL . 'assets/js/bulk-ordering.js',
            [],
            B2B_PLUGIN_VERSION,
            true
        );

        // ✅ Enqueue WooCommerce cart fragment support
        wp_enqueue_script('wc-cart-fragments');


        // Make script a module
        add_filter('script_loader_tag', function ($tag, $handle, $src) {
            if ($handle === 'b2b-bulk-ordering') {
                return "<script type=\"module\" src=\"" . esc_url($src) . "\"></script>";
            }
            return $tag;
        }, 10, 3);


        // Collect variation data for all variable products
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
                $variation_data[$product_id] = $variations;
            }
        }

        // Localize main plugin data
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
}
