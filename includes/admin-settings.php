<?php
class WC_Product_Attachments_Admin_Settings {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_settings_page() {
        add_submenu_page(
            'woocommerce',
            'Product Attachments Settings',
            'Product Attachments',
            'manage_options',
            'product-attachments-settings',
            array($this, 'settings_page_html')
        );
    }

    public function register_settings() {
        register_setting('product_attachments_settings', 'pa_tab_title');
        register_setting('product_attachments_settings', 'pa_shortcode_title');
        register_setting('product_attachments_settings', 'pa_display_method');
        register_setting('product_attachments_settings', 'pa_hide_if_empty'); // Register new setting
    }

    public function settings_page_html() {
        ?>
        <div class="wrap">
            <h1>Product Attachments Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('product_attachments_settings'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">Display Method</th>
                        <td>
                            <select name="pa_display_method">
                                <option value="tab" <?php selected(get_option('pa_display_method'), 'tab'); ?>>Product Tab</option>
                                <option value="shortcode" <?php selected(get_option('pa_display_method'), 'shortcode'); ?>>Shortcode</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Tab Title</th>
                        <td>
                            <input type="text" name="pa_tab_title" value="<?php echo esc_attr(get_option('pa_tab_title', 'Downloads')); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Shortcode Title</th>
                        <td>
                            <input type="text" name="pa_shortcode_title" value="<?php echo esc_attr(get_option('pa_shortcode_title', 'Downloads')); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Hide if Empty</th>
                        <td>
                            <input type="checkbox" name="pa_hide_if_empty" value="1" <?php checked(get_option('pa_hide_if_empty'), 1); ?> />
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
            <p>Shortcode: [product_attachments]</p>
        </div>
        <?php
    }
}