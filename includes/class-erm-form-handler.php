<?php
class ERM_Form_Handler {
    private $db;
    
    public function __construct() {
        $this->db = new ERM_Database();
        add_shortcode('educational_resource_form', array($this, 'display_form'));
    }
    
    public function display_form() {
        ob_start();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_resource'])) {
            $this->handle_submission();
        }
        
        include ERM_PLUGIN_DIR . 'templates/forms/submission-form.php';
        return ob_get_clean();
    }
    
    private function handle_submission() {
        $data = array(
            'title' => sanitize_text_field($_POST['title']),
            'subtitle' => sanitize_text_field($_POST['subtitle']),
            'category' => sanitize_text_field($_POST['category']),
            'author' => sanitize_text_field($_POST['author']),
            'author_email' => sanitize_email($_POST['author_email']),
            'origin' => sanitize_text_field($_POST['origin']),
            'country' => sanitize_text_field($_POST['country']),
            'knowledge_area' => sanitize_text_field($_POST['knowledge_area']),
            'knowledge_area_other' => sanitize_text_field($_POST['knowledge_area_other']),
            'description' => sanitize_textarea_field($_POST['description']),
            'publication_date' => sanitize_text_field($_POST['publication_date']),
            'last_update' => current_time('Y-m-d'),
            'language' => sanitize_text_field($_POST['language']),
            'school_sequence' => sanitize_text_field($_POST['school_sequence']),
            'level_other_countries' => sanitize_text_field($_POST['level_other_countries']),
            'file_type' => sanitize_text_field($_POST['file_type']),
            'visual_format' => sanitize_text_field($_POST['visual_format']),
            'target_user' => sanitize_text_field($_POST['target_user']),
            'license' => sanitize_text_field($_POST['license']),
            'cab_rating' => sanitize_text_field($_POST['cab_rating']),
            'cab_seal' => sanitize_text_field($_POST['cab_seal'])
        );
        
        $result = $this->db->insert_resource($data);
        
        if ($result === false) {
            echo '<div class="notice notice-error"><p>Error al enviar el recurso.</p></div>';
        } else {
            echo '<div class="notice notice-success"><p>¡Recurso enviado exitosamente!</p></div>';
        }
    }
}