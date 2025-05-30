<?php
/**
 * Plugin Name:       WooCommerce B2B Bulk Ordering
 * Description:       A streamlined bulk ordering and product browsing interface for B2B customers using WooCommerce.
 * Version:           1.0.2
 * Author:            M.L Web Solutions
 * Author URI:        https://clients.libiserv.co.il/
 * Text Domain:       bulk-ordering
 * Domain Path:       /languages
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * WC requires at least: 6.0
 * WC tested up to:   8.9
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// ✅ Define plugin constants
define('B2B_PLUGIN_VERSION', '1.0.2');
define('B2B_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('B2B_PLUGIN_URL', plugin_dir_url(__FILE__));
define('B2B_PLUGIN_BASENAME', plugin_basename(__FILE__));

// ✅ Autoload or manually include core files
require_once B2B_PLUGIN_DIR . 'includes/class-b2b-loader.php';

// ✅ Initialize plugin
function b2b_bulk_ordering_init()
{
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p><strong>WooCommerce B2B Bulk Ordering</strong> requires WooCommerce to be active.</p></div>';
        });

        // Optional: log for headless or CLI debugging
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[Woo B2B Bulk Ordering] WooCommerce is not active.');
        }

        return;
    }

    $plugin = new \B2B\BulkOrdering\B2B_Loader();
    $plugin->init();
}
add_action('plugins_loaded', 'b2b_bulk_ordering_init');

// ✅ Load plugin text domain
function b2b_bulk_ordering_load_textdomain()
{
    load_plugin_textdomain('bulk-ordering', false, dirname(B2B_PLUGIN_BASENAME) . '/languages');
}
add_action('init', 'b2b_bulk_ordering_load_textdomain');

// ✅ Register plugin activation hook
function b2b_bulk_ordering_activate()
{
    // Future: add activation logic like DB setup, options init, etc.
}
register_activation_hook(__FILE__, 'b2b_bulk_ordering_activate');

// ✅ Register plugin deactivation hook (optional)
function b2b_bulk_ordering_deactivate()
{
    // Future: cleanup tasks if needed
}
register_deactivation_hook(__FILE__, 'b2b_bulk_ordering_deactivate');
