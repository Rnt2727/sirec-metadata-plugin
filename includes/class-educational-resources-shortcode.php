<?php
class Educational_Resources_Shortcode {
    public function render_form() {
        ob_start();
        include EDUCATIONAL_RESOURCES_PLUGIN_DIR . 'templates/submission-form.php';
        return ob_get_clean();
    }
    
    public function process_form() {
        if (!isset($_POST['educational_resource_nonce']) || 
            !wp_verify_nonce($_POST['educational_resource_nonce'], 'submit_educational_resource')) {
            return;
        }
        
        // Process form submission and save to database
        global $wpdb;
        
        $data = array(
            'title' => sanitize_text_field($_POST['title']),
            'subtitle' => sanitize_text_field($_POST['subtitle']),
            'category' => sanitize_text_field($_POST['category']),
            // Add all other fields...
        );
        
        $wpdb->insert($wpdb->prefix . 'educational_resources', $data);
    }
}