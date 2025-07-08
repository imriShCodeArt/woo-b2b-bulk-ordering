<?php

namespace B2B\BulkOrdering;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class B2B_Cart_Handler
 * Handles bulk add-to-cart and product filtering via AJAX.
 */
class B2B_Cart_Handler
{
    /**
     * Handles AJAX add-to-cart requests for bulk items.
     *
     * @return void
     */
    public function handle(): void
    {
        if (!$this->verify_nonce('b2b_bulk_order', $_POST['nonce'] ?? '')) {
            $this->respond_nonce_error('b2b_bulk_order', $_POST['nonce'] ?? 'none');
        }

        $items = isset($_POST['items']) ? (array) $_POST['items'] : [];

        if (empty($items)) {
            wp_send_json_error(['message' => __('No items to add.', 'bulk-ordering')]);
        }

        $this->clear_cart();

        [$errors, $added_names] = $this->process_cart_items($items);

        if (!empty($errors)) {
            wp_send_json_error([
                'message' => __('Some products could not be added.', 'bulk-ordering'),
                'failed'  => $errors,
            ]);
        }

        wp_send_json_success([
            'message'  => __('All products added to cart.', 'bulk-ordering'),
            'products' => $added_names,
        ]);
    }

    // #region 🔄 Core Operations

    /**
     * Empties the current WooCommerce cart.
     */
    private function clear_cart(): void
    {
        WC()->cart->empty_cart();
    }

    /**
     * Attempts to add all items to cart.
     * Returns a tuple: [failed product IDs, added product names].
     *
     * @param array $items
     * @return array [array<int>, array<string>]
     */
    private function process_cart_items(array $items): array
    {
        $errors = [];
        $added  = [];

        foreach ($items as $item) {
            $product_id   = isset($item['product_id']) ? absint($item['product_id']) : 0;
            $quantity     = isset($item['quantity']) ? absint($item['quantity']) : 0;
            $variation_id = isset($item['variation_id']) ? absint($item['variation_id']) : 0;
            $attributes   = isset($item['attributes']) ? (array) $item['attributes'] : [];

            if ($quantity <= 0 || $product_id <= 0) {
                continue;
            }

            $added_result = ($variation_id > 0)
                ? WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $attributes)
                : WC()->cart->add_to_cart($product_id, $quantity);

            if (!$added_result) {
                $errors[] = $product_id;
                continue;
            }

            $product = wc_get_product($variation_id ?: $product_id);
            $added[] = $product ? $product->get_name() : __('Unknown product', 'bulk-ordering');
        }

        return [$errors, $added];
    }

    // #endregion

    // #region 🔍 Filter Endpoint

    /**
     * Registers AJAX endpoints.
     */
    public static function register_ajax_hooks(): void
    {
        add_action('wp_ajax_b2b_filter_products', [self::class, 'ajax_filter_products']);
        add_action('wp_ajax_nopriv_b2b_filter_products', [self::class, 'ajax_filter_products']);
    }

    /**
     * AJAX handler for filtered product list rendering.
     */
    public static function ajax_filter_products(): void
    {
        $handler = new self();

        if (!$handler->verify_nonce('b2b_filter_nonce', $_POST['nonce'] ?? '')) {
            $handler->respond_nonce_error('b2b_filter_nonce', $_POST['nonce'] ?? 'none');
        }

        $cat = isset($_POST['product_cat']) ? sanitize_text_field($_POST['product_cat']) : '';

        try {
            $query = new B2B_Product_Query($cat);
            $loop  = $query->get_query();

            $html = self::render_product_loop($loop);
            wp_send_json_success(['html' => $html]);
        } catch (\Throwable $e) {
            error_log("❌ AJAX filter fatal error: " . $e->getMessage());
            wp_send_json_error(['message' => __('Server error while filtering products.', 'bulk-ordering')]);
        }
    }

    /**
     * Renders filtered product results using the template partial.
     *
     * @param \WP_Query $loop
     * @return string
     */
    private static function render_product_loop(\WP_Query $loop): string
    {
        ob_start();

        if ($loop->have_posts()) {
            while ($loop->have_posts()) {
                $loop->the_post();
                global $product;

                $partial_path = plugin_dir_path(__DIR__) . '/../templates/partials/product-item.php';

                if (file_exists($partial_path)) {
                    include $partial_path;
                } else {
                    error_log("❌ Missing partial: $partial_path");
                    echo '<p>' . esc_html__('Template error.', 'bulk-ordering') . '</p>';
                }
            }
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__('No products found.', 'bulk-ordering') . '</p>';
        }

        return ob_get_clean();
    }

    // #endregion

    // #region 🔐 Security

    /**
     * Verifies a nonce.
     *
     * @param string $action
     * @param string $received_nonce
     * @return bool
     */
    private function verify_nonce(string $action, string $received_nonce): bool
    {
        return wp_verify_nonce($received_nonce, $action);
    }

    /**
     * Sends an AJAX error response for nonce failure.
     *
     * @param string $action
     * @param string $received
     * @return void
     */
    private function respond_nonce_error(string $action, string $received): void
    {
        wp_send_json_error([
            'message'              => __('Invalid security token.', 'bulk-ordering'),
            'debug_nonce_received' => $received,
            'debug_nonce_expected' => '🔐 (Value not shown to avoid confusion)',
        ]);
    }

    // #endregion
}
