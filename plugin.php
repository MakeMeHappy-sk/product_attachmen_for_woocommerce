<?php
/*
Plugin Name: Product Attachment for WooCommerce
Plugin URI: https://makemehappy.sk/produkt/product-attachment-for-woocommerce/
Description: Add downloadable attachments to WooCommerce products
Version: 1.2
Author: MakeMeHappy
Author URI: https://makemehappy.sk
Donate link: https://makemehappy.sk/donate
License: GPLv2
*/

if (!defined('ABSPATH')) exit;

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/product-meta.php';
require_once plugin_dir_path(__FILE__) . 'includes/frontend-display.php';

class WC_Product_Attachments {
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles')); // Nový hook
        add_filter('plugin_row_meta', array($this, 'add_donate_link'), 10, 2); // Add this line
    }

    public function init() {
        new WC_Product_Attachments_Admin_Settings();
        new WC_Product_Attachments_Meta();
        new WC_Product_Attachments_Frontend();
    }

    // Pridajte túto novú metódu
    public function enqueue_styles() {
        wp_enqueue_style(
            'product-attachments-css',
            plugin_dir_url(__FILE__) . 'css/product-attachments.css',
            array(),
            filemtime(plugin_dir_path(__FILE__) . 'css/product-attachments.css')
        );
    }

    // Add this new method
    public function add_donate_link($links, $file) {
        if ($file == plugin_basename(__FILE__)) {
            $links[] = '<a href="https://makemehappy.sk/donate" target="_blank">Donate</a>';
        }
        return $links;
    }
}

new WC_Product_Attachments();