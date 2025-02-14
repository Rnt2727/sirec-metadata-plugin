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

        add_action('wp_ajax_update_resource', array($this, 'handle_resource_update'));
        add_action('wp_ajax_nopriv_update_resource', array($this, 'handle_resource_update'));

        add_action('wp_ajax_approve_resource', array($this, 'handle_resource_approval'));
        add_action('wp_ajax_reject_resource', array($this, 'handle_resource_rejection'));
        add_action('wp_ajax_nopriv_reject_resource', array($this, 'handle_resource_rejection'));
    }

    public function handle_resource_approval() {
        check_ajax_referer('update_resource', 'nonce');
        
        $resource_id = intval($_POST['resource_id']);
        $author_email = sanitize_email($_POST['author_email']);
        
        $data = array(
            'approved_by_catalogator' => 1,
            'rejection_reason' => null
        );
        
        $result = $this->db->update_resource($resource_id, $data);
        
        if ($result !== false) {
            // Aquí podrías agregar código para enviar un email al autor
            wp_send_json_success(array('message' => 'Recurso aprobado exitosamente'));
        } else {
            wp_send_json_error('Error al aprobar el recurso');
        }
    }
    
    public function handle_resource_rejection() {
        check_ajax_referer('update_resource', 'nonce');
        
        $resource_id = intval($_POST['resource_id']);
        $author_email = sanitize_email($_POST['author_email']);
        $rejection_reason = sanitize_textarea_field($_POST['rejection_reason']);
        
        // Actualizar el estado del recurso en la base de datos
        $data = array(
            'approved_by_catalogator' => 0,
            'rejection_reason' => $rejection_reason
        );
        
        $result = $this->db->update_resource($resource_id, $data);
        
        if ($result !== false) {
            // Enviar correo electrónico al autor
            $subject = 'Tu recurso educativo ha sido rechazado';
            $message = sprintf(
                'Estimado autor,
    
    Tu recurso educativo (ID: %d) ha sido revisado y no ha sido aprobado.
    
    Razón del rechazo:
    %s
    
    Si tienes alguna pregunta, por favor contáctanos.
    
    Saludos cordiales,
    El equipo de SIREC',
                $resource_id,
                $rejection_reason
            );
            
            $headers = array('Content-Type: text/html; charset=UTF-8');
            
            $email_sent = wp_mail($author_email, $subject, $message, $headers);
            
            if ($email_sent) {
                wp_send_json_success(array(
                    'message' => 'Recurso rechazado y notificación enviada exitosamente',
                    'email_sent' => true
                ));
            } else {
                wp_send_json_success(array(
                    'message' => 'Recurso rechazado pero hubo un problema al enviar la notificación',
                    'email_sent' => false
                ));
            }
        } else {
            wp_send_json_error('Error al rechazar el recurso');
        }
    }
    
    public function display_form() {
        ob_start();
        
        // Incluir el modal
        include ERM_PLUGIN_DIR . 'templates/forms/confirmation-modal.php';
        include ERM_PLUGIN_DIR . 'templates/forms/submission-form.php';
        
        return ob_get_clean();
    }

    public function handle_resource_update() {

        // Verificar el nonce

        if (!check_ajax_referer('update_resource', 'nonce', false)) {

            wp_send_json_error('Invalid nonce');

            return;

        }

    

        // Verificar que tenemos los datos necesarios

        if (!isset($_POST['resource_id']) || !isset($_POST['resource_data'])) {

            wp_send_json_error('Missing required data');

            return;

        }

    

        $resource_id = intval($_POST['resource_id']);

        $resource_data = $_POST['resource_data'];

    

        // Sanitizar los datos

        $sanitized_data = array();

        foreach ($resource_data as $key => $value) {

            if ($key === 'description') {

                $sanitized_data[$key] = sanitize_textarea_field($value);

            } else {

                $sanitized_data[$key] = sanitize_text_field($value);

            }

        }

    

        // Actualizar el recurso

        $result = $this->db->update_resource($resource_id, $sanitized_data);

    

        if ($result !== false) {

            wp_send_json_success(array(

                'message' => 'Recurso actualizado exitosamente',

                'data' => $sanitized_data

            ));

        } else {

            wp_send_json_error('Error al actualizar el recurso');

        }

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
            'skills_competencies' => sanitize_text_field($_POST['skills_competencies']),
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