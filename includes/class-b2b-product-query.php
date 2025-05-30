<?php
namespace B2B\BulkOrdering;

if (!defined('ABSPATH')) {
    exit;
}

class B2B_Product_Query
{
    private ?string $product_cat;
    // private ?string $product_tag;

    public function __construct(?string $product_cat = null)
    {
        $this->product_cat = sanitize_text_field($product_cat ?? ($_GET['product_cat'] ?? ''));
        // $this->product_tag = sanitize_text_field($product_tag ?? ($_GET['product_tag'] ?? ''));

    }

    /**
     * Builds and returns a filtered product query.
     *
     * @param array $args Optional override args.
     * @return \WP_Query
     */
    public function get_query(array $args = []): \WP_Query
    {
        $default_args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 20,
            'paged' => get_query_var('paged') ?: 1,
            'meta_query' => WC()->query->get_meta_query(),
            'tax_query' => $this->get_tax_query(),
        ];

        return new \WP_Query(array_merge($default_args, $args));
    }

    /**
     * Constructs taxonomy query based on selected filters.
     *
     * @return array
     */
    private function get_tax_query(): array
    {
        $tax_query = WC()->query->get_tax_query(); // Includes visibility rules

        if (!empty($this->product_cat)) {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => sanitize_text_field($this->product_cat),
            ];
        }

        // if (!empty($this->product_tag)) {
        //     $tax_query[] = [
        //         'taxonomy' => 'product_tag',
        //         'field' => 'slug',
        //         'terms' => sanitize_text_field($this->product_tag),
        //     ];
        // }

        return $tax_query;
    }

    public static function get_all_products()
    {
        $args = [
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC',
        ];
        return wc_get_products($args);
    }


}
