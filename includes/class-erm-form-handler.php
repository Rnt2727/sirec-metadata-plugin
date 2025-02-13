<?php
class ERM_Form_Handler {
    private $db;
    private $form_submitted = false;
    
    public function __construct() {
        $this->db = new ERM_Database();
        add_shortcode('educational_resource_form', array($this, 'display_form'));
    }
    
    public function display_form() {
        ob_start();
        
        // Verificar nonce para seguridad
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
            isset($_POST['submit_resource']) && 
            isset($_POST['resource_form_nonce']) && 
            wp_verify_nonce($_POST['resource_form_nonce'], 'submit_resource_form')) {
            
            $this->handle_submission();
        }
        
        // Solo mostrar el mensaje si el formulario fue enviado exitosamente
        if ($this->form_submitted) {
            echo '<div class="notice notice-success"><p>¡Recurso enviado exitosamente!</p></div>';
        }
        
        include ERM_PLUGIN_DIR . 'templates/forms/submission-form.php';
        return ob_get_clean();
    }
    
    private function handle_submission() {
        // Validar que todos los campos requeridos estén presentes
        if (empty($_POST['title']) || empty($_POST['author']) || empty($_POST['author_email'])) {
            echo '<div class="notice notice-error"><p>Por favor complete todos los campos requeridos.</p></div>';
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
            $this->form_submitted = true;
            
            // Redirigir para evitar reenvíos al refrescar
            wp_redirect(add_query_arg('submitted', 'true', wp_get_referer()));
            exit;
        }
    }
}