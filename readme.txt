# 🛒 WooCommerce B2B Bulk Ordering

A lightweight and responsive bulk ordering system for WooCommerce, built for B2B and wholesale stores. Let your customers quickly add multiple products—including variable ones—to their cart from a single interface.

---

## 📦 Features

- ✅ Shortcode-powered UI: `[b2b_bulk_ordering]`
- ✅ Add multiple products to cart in one click
- ✅ Fully compatible with variable products
- ✅ AJAX-based cart processing with WooCommerce fragment refresh
- ✅ Filter products by category (tag support planned)
- ✅ Responsive layout with sticky CTA button
- ✅ Theme override support for templates
- ✅ Modular JS and PHP structure
- ✅ Translation-ready (`.pot` file included)

---

## 🚀 Getting Started

### 1. Installation

Clone or download this repo and place it in your `/wp-content/plugins/` directory:

```bash
git clone https://github.com/your-username/woo-b2b-bulk-ordering.git
```

Then activate it from your WordPress admin dashboard.

---

### 2. Usage

Add the following shortcode to any post or page:

```text
[b2b_bulk_ordering]
```

That’s it! The interface will automatically render and load your store's products.

---

## 🧰 Developer Notes

### File Structure

```
woo-b2b-bulk-ordering/
├── assets/                 # CSS & JS
│   ├── css/bulk-ordering.css
│   └── js/
│       ├── bulk-ordering.js
│       ├── handlers/
│       └── utils/
├── includes/              # PHP Classes
├── templates/             # Overridable HTML templates
│   ├── bulk-ordering-ui.php
│   └── partials/
├── languages/             # .pot translation file
├── woo-b2b-bulk-ordering.php
```

### Template Overrides

To override a template, copy it to your theme:

```
your-theme/
└── woo-b2b-bulk-ordering/
    └── bulk-ordering-ui.php
```

---

## 📚 Available Hooks

### PHP

- `b2b_bulk_ordering_template`  
  Override the default template path.

### JavaScript

- `added_to_cart`  
  Triggered after successful cart update.

---

## 🎯 Ideal Use Cases

- Wholesale shops
- Internal order forms
- Quick reordering portals
- Restaurant supply, office supply, etc.

---

## 🧩 Planned Features

- [ ] Tag-based filtering
- [ ] CSV product import/export
- [ ] Order summary before submission
- [ ] Settings UI in admin

---

## 📄 License

MIT — use this freely in personal or commercial projects.

---

## 🤝 Contributing

Pull requests are welcome! For major changes, please open an issue first to discuss what you’d like to change.

---

## 🙌 Credits

Crafted with ❤️ for WooCommerce site builders focused on speed and usability.
