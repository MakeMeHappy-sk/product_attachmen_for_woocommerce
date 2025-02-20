<?php
class WC_Product_Attachments_Meta {
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save_attachments'));
    }

    public function add_meta_box() {
        add_meta_box(
            'product_attachments',
            'Product Attachments',
            array($this, 'meta_box_html'),
            'product',
            'normal',
            'default'
        );
    }

    public function meta_box_html($post) {
        $attachments = get_post_meta($post->ID, '_product_attachments', true);
        wp_nonce_field('pa_save_attachments', 'pa_nonce');
        ?>
        <div id="pa-attachments-container">
            <?php if (!empty($attachments)) : ?>
                <?php foreach ($attachments as $index => $attachment) : ?>
                    <div class="pa-attachment">
                        <input type="text" name="pa_button_label[]" value="<?php echo esc_attr($attachment['label']); ?>" placeholder="Button Label" />
                        <input type="text" name="pa_file_url[]" class="pa-file-url" value="<?php echo esc_url($attachment['url']); ?>" />
                        <button class="button pa-upload">Upload/Select File</button>
                        <button class="button pa-remove">Remove</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button class="button" id="pa-add-attachment">Add Attachment</button>
        <script>
            jQuery(document).ready(function($) {
                // Pridanie nového attachmentu
                $('#pa-add-attachment').click(function(e) {
                    e.preventDefault();
                    var newAttachment = `<div class="pa-attachment">
                        <input type="text" name="pa_button_label[]" placeholder="Button Label" />
                        <input type="text" name="pa_file_url[]" class="pa-file-url" />
                        <button class="button pa-upload">Vybrať súbor</button>
                        <button class="button pa-remove">Odstrániť</button>
                    </div>`;
                    $('#pa-attachments-container').append(newAttachment);
                });

                // Uploader
                $(document).on('click', '.pa-upload', function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var frame = wp.media({
                        title: 'Vyberte súbor',
                        multiple: false
                    });
                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        button.siblings('.pa-file-url').val(attachment.url);
                    });
                    frame.open();
                });

                // Odstránenie attachmentu
                $(document).on('click', '.pa-remove', function(e) {
                    e.preventDefault();
                    $(this).closest('.pa-attachment').remove();
                });
            });
        </script>
        <?php
    }

    public function save_attachments($post_id) {
        // Oprava pre pa_nonce
        $nonce = isset($_POST['pa_nonce']) ? sanitize_text_field(wp_unslash($_POST['pa_nonce'])) : '';
        if (!wp_verify_nonce($nonce, 'pa_save_attachments')) return;
    
        // Oprava pre pa_button_label
        $labels = isset($_POST['pa_button_label']) 
            ? array_map('sanitize_text_field', wp_unslash($_POST['pa_button_label'])) 
            : array();
    
        // Oprava pre pa_file_url
        $urls = isset($_POST['pa_file_url']) 
            ? array_map('esc_url_raw', wp_unslash($_POST['pa_file_url'])) 
            : array();
    
        $attachments = array();
        foreach ($urls as $index => $url) {
            if (!empty($url)) {
                $attachments[] = array(
                    'label' => $labels[$index] ?? '', // sanitizované už vyššie
                    'url' => $url // escapované už vyššie
                );
            }
        }
        
        update_post_meta($post_id, '_product_attachments', $attachments);
    }
}