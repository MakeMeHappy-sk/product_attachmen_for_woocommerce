<?php
class WC_Product_Attachments_Frontend {
    public function __construct() {
        $display_method = get_option('pa_display_method', 'tab');
        
        if ($display_method === 'tab') {
            add_filter('woocommerce_product_tabs', array($this, 'add_attachments_tab'));
        }
        
        add_shortcode('product_attachments', array($this, 'shortcode_display'));
    }

    public function add_attachments_tab($tabs) {
        $tabs['product_attachments'] = array(
            'title' => get_option('pa_tab_title', 'Downloads'),
            'priority' => 50,
            'callback' => array($this, 'attachments_tab_content')
        );
        return $tabs;
    }

    public function attachments_tab_content() {
        $this->display_attachments();
    }

    public function shortcode_display() {
        ob_start();
        $title = get_option('pa_shortcode_title', 'Downloads');
        echo '<h3>' . esc_html($title) . '</h3>';
        $this->display_attachments();
        return ob_get_clean();
    }

    private function display_attachments() {
        global $product;
        if (!$product) {
            return; // Ensure $product is not null
        }
        $attachments = get_post_meta($product->get_id(), '_product_attachments', true);
        
        if (!empty($attachments)) {
            echo '<div class="product-attachments">';
            echo '<div class="product-attachments-list">'; // Nový wrapper
            foreach ($attachments as $attachment) {
                echo '<a href="' . esc_url($attachment['url']) . '" class="button pa-download" download>';
                echo esc_html($attachment['label']);
                echo '</a>';
            }
            echo '</div></div>'; // Zatvorenie wrapperov
        }
    }
}