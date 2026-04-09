🛒 WooCommerce B2B Bulk Ordering
================================

A lightweight, responsive bulk ordering system for WooCommerce—built for B2B and wholesale workflows. Enables customers to quickly add multiple products (including variable products) to the cart from a single interface.

* * *

📦 Features
-----------

*   Shortcode-powered UI: `[b2b_bulk_ordering]`
*   Add multiple products to cart in a single action
*   Full support for variable products
*   AJAX-based cart handling with WooCommerce fragment refresh
*   Product filtering by category (tag support planned)
*   Responsive layout with sticky CTA
*   Template override support
*   Modular PHP and JavaScript architecture
*   Translation-ready (includes `.pot` file)

* * *

🚀 Getting Started
------------------

### 1\. Installation

Clone or download the repository into your WordPress plugins directory:

    git clone https://github.com/<your-username>/woo-b2b-bulk-ordering.git

Then activate the plugin from the WordPress admin dashboard.

* * *

### 2\. Usage

Add the shortcode to any post or page:

    [b2b_bulk_ordering]

The interface will render automatically and load products dynamically.

* * *

🧰 Developer Notes
------------------

### File Structure

    woo-b2b-bulk-ordering/
    ├── assets/
    │   ├── css/bulk-ordering.css
    │   └── js/
    │       ├── bulk-ordering.js
    │       ├── handlers/
    │       └── utils/
    ├── includes/
    ├── templates/
    │   ├── bulk-ordering-ui.php
    │   └── partials/
    ├── languages/
    ├── woo-b2b-bulk-ordering.php

### Template Overrides

To override templates, copy them into your theme:

    your-theme/
    └── woo-b2b-bulk-ordering/
        └── bulk-ordering-ui.php

* * *

📚 Available Hooks
------------------

### PHP

*   `b2b_bulk_ordering_template`  
    Override the default template path.

### JavaScript

*   `added_to_cart`  
    Triggered after a successful cart update.

* * *

🎯 Use Cases
------------

*   Wholesale stores
*   Internal ordering systems
*   Rapid reordering interfaces
*   Industry-specific supply portals (e.g. food, office, retail)

* * *

🧩 Roadmap
----------

*   Tag-based product filtering
*   CSV import/export
*   Pre-submit order summary
*   Admin configuration UI

* * *

📄 License
----------

MIT License. See `LICENSE` file for details.

* * *

🤝 Contributing
---------------

Pull requests are welcome. For significant changes, open an issue first to discuss scope and approach.

* * *

🙌 Credits
----------

Built for WooCommerce environments where speed, efficiency, and usability are critical.
