<?php
class ERM_Form_Handler {
    private $db;
    private $form_submitted = false;
    private $submission_message = '';
    
    public function __construct() {
        $this->db = new ERM_Database();
        add_shortcode('educational_resource_form', array($this, 'display_form'));
        
        // Agregar manejo de AJAX
        add_action('wp_ajax_submit_resource', array($this, 'handle_ajax_submission'));
        add_action('wp_ajax_nopriv_submit_resource', array($this, 'handle_ajax_submission'));
    }
    
    public function display_form() {
        ob_start();
        
        // Incluir el modal
        include ERM_PLUGIN_DIR . 'templates/forms/confirmation-modal.php';
        include ERM_PLUGIN_DIR . 'templates/forms/submission-form.php';
        
        return ob_get_clean();
    }
    
    public function handle_ajax_submission() {
        check_ajax_referer('submit_resource_form', 'nonce');
        
        $response = array('success' => false, 'message' => '');
        
        // Validar campos requeridos
        if (empty($_POST['title']) || empty($_POST['author']) || empty($_POST['author_email'])) {
            $response['message'] = 'Por favor complete todos los campos requeridos.';
            wp_send_json($response);
            return;
        }
        
        $data = array(
            'title' => sanitize_text_field($_POST['title']),
            'subtitle' => sanitize_text_field($_POST['subtitle']),
            'category' => sanitize_text_field($_POST['category']),
            'author' => sanitize_text_field($_POST['author']),
            'author_email' => sanitize_email($_POST['author_email']),
            'origin' => sanitize_text_field($_POST['origin']),
            'country' => sanitize_text_field($_POST['country']),
            'knowledge_area' => sanitize_text_field($_POST['knowledge_area']),
            'knowledge_area_other_countries' => sanitize_text_field($_POST['knowledge_area_other_countries']),
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
            'cab_seal' => sanitize_text_field($_POST['cab_seal']),
            'age' => sanitize_text_field($_POST['age']),
        );
        
        $result = $this->db->insert_resource($data);
        
        if ($result) {
            $response['success'] = true;
            $response['message'] = '¡Recurso enviado exitosamente!';
        } else {
            $response['message'] = 'Hubo un error al guardar el recurso.';
        }
        
        wp_send_json($response);
    }
}