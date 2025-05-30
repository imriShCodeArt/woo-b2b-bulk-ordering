<?php
namespace B2B\BulkOrdering;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class B2B_Product_Query
 *
 * Builds filtered product queries based on category (and future: tags).
 */
class B2B_Product_Query
{
    private ?string $product_cat;

    // Future: private ?string $product_tag;

    /**
     * Constructor.
     *
     * @param string|null $product_cat Optional category slug.
     */
    public function __construct(?string $product_cat = null)
    {
        $this->product_cat = $this->sanitize_filter($product_cat ?? ($_GET['product_cat'] ?? null));
        // $this->product_tag = $this->sanitize_filter($product_tag ?? ($_GET['product_tag'] ?? null));
    }

    /**
     * Sanitize input filter values.
     *
     * @param string|null $value
     * @return string|null
     */
    private function sanitize_filter(?string $value): ?string
    {
        return $value !== null ? sanitize_text_field($value) : null;
    }

    /**
     * Returns a paginated and filtered product WP_Query object.
     *
     * @param array $args Optional override arguments.
     * @return \WP_Query
     */
    public function get_query(array $args = []): \WP_Query
    {
        $default_args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => 20,
            'paged'          => max(1, get_query_var('paged')),
            'meta_query'     => WC()->query->get_meta_query(),
            'tax_query'      => $this->get_tax_query(),
        ];

        return new \WP_Query(array_merge($default_args, $args));
    }

    /**
     * Builds a tax_query array with WooCommerce visibility rules and optional filters.
     *
     * @return array
     */
    private function get_tax_query(): array
    {
        $tax_query = WC()->query->get_tax_query(); // Includes visibility settings

        if (!empty($this->product_cat)) {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $this->product_cat,
            ];
        }

        // Future tag filter support:
        // if (!empty($this->product_tag)) {
        //     $tax_query[] = [
        //         'taxonomy' => 'product_tag',
        //         'field'    => 'slug',
        //         'terms'    => $this->product_tag,
        //     ];
        // }

        return $tax_query;
    }

    /**
     * Retrieves all published products, sorted by title.
     *
     * @return \WC_Product[]
     */
    public static function get_all_products(): array
    {
        return wc_get_products([
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
        ]);
    }
}
