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

        // Otros AJAX (update, approve, reject, evaluacion)
        add_action('wp_ajax_update_resource', array($this, 'handle_resource_update'));
        add_action('wp_ajax_nopriv_update_resource', array($this, 'handle_resource_update'));
        add_action('wp_ajax_approve_resource', array($this, 'handle_resource_approval'));
        add_action('wp_ajax_reject_resource', array($this, 'handle_resource_rejection'));
        add_action('wp_ajax_nopriv_reject_resource', array($this, 'handle_resource_rejection'));
        add_action('wp_ajax_save_evaluation', array($this, 'handle_evaluation_submission'));
    }

    public function handle_evaluation_submission() {
        try {
            check_ajax_referer('update_resource', 'nonce');
            
            if (!isset($_POST['resource_id']) || !isset($_POST['evaluation_data'])) {
                wp_send_json_error('Datos incompletos');
                return;
            }
            
            $resource_id = intval($_POST['resource_id']);
            $evaluation_data = $_POST['evaluation_data'];
            
            if (!is_array($evaluation_data)) {
                wp_send_json_error('Formato de datos inválido');
                return;
            }
            
            $evaluator = new ERM_Evaluator();
            $score = $evaluator->calculate_evaluation_score($evaluation_data);
            
            if ($score === null) {
                wp_send_json_error('No hay preguntas válidas para calcular el puntaje');
                return;
            }
    
            $seal = $evaluator->determine_seal($score);
            
            $update_data = array(
                'evaluation_score' => $score,
                'cab_seal' => $seal
            );
            
            $update_result = $this->db->update_resource($resource_id, $update_data);
            
            if ($update_result !== false) {
                wp_send_json_success(array(
                    'message' => 'Evaluación guardada exitosamente',
                    'score' => $score,
                    'seal' => $seal
                ));
            } else {
                wp_send_json_error('Error al guardar la evaluación en la base de datos');
            }
        } catch (Exception $e) {
            wp_send_json_error('Error: ' . $e->getMessage());
        }
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
        $resource = $this->db->get_resource_by_id($resource_id);
        $data = array('approved_by_catalogator' => 0, 'rejection_reason' => $rejection_reason);
        $result = $this->db->update_resource($resource_id, $data);
        
        if ($result !== false) {
            $subject = 'Tu recurso educativo ha sido rechazado - SIREC';
            $message = '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style>
                    @media only screen and (max-width: 600px) {
                        .content-table td {display: block; width: 100% !important;}
                    }
                </style>
            </head>
            <body style="margin:0;padding:0;font-family:\'Montserrat\',Arial,sans-serif;background-color:#f8f9fa;">
                <table role="presentation" style="width:100%;border-collapse:collapse;background-color:#ffffff;">
                    <tr>
                        <td style="padding:30px 0;text-align:center;border-bottom:3px solid #0093D0;">
                            <img src="https://convenioandresbello.org/wp-content/uploads/2023/11/logo_CAB_2024.png" alt="Logo CAB" style="max-width:280px;height:auto;">
                        </td>
                    </tr>
                </table>
                
                <table role="presentation" style="max-width:600px;margin:30px auto;background-color:#ffffff;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="padding:40px 30px;">
                            <h1 style="color:#1A3D8F;font-size:26px;margin-bottom:25px;border-bottom:2px solid #0093D0;padding-bottom:15px;font-weight:600;">
                                Notificación de Rechazo de Recurso Educativo
                            </h1>
                            
                            <p style="color:#4a5568;font-size:16px;line-height:1.6;margin-bottom:30px;">
                                Estimado/a ' . esc_html($resource->author) . ',
                            </p>
                            
                            <div style="background-color:#f8f9fa;border-left:4px solid #0093D0;padding:20px;border-radius:8px;margin-bottom:30px;">
                                <p style="color:#1A3D8F;font-size:18px;font-weight:600;margin:0;">Motivo del rechazo:</p>
                                <p style="color:#4a5568;font-size:16px;margin-top:10px;">' . esc_html($rejection_reason) . '</p>
                            </div>
                            
                            <h2 style="color:#1A3D8F;font-size:20px;margin-top:30px;">Detalles del Recurso:</h2>
                            <table style="width:100%;border-collapse:collapse;margin-top:15px;">
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">ID:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->id) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Título:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->title) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Subtítulo:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->subtitle) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Categoría:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->category) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Autor:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->author) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Email:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->author_email) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Procedencia:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->origin) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">País:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->country) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Área de Conocimiento:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->knowledge_area) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Áreas en Otros Países:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->knowledge_area_other_countries) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Descripción:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->description) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Fecha de Publicación:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->publication_date) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Idioma:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->language) . '</td></tr>
                                <tr>
                                    <td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Nivel escolar:</td>
                                    <td style="padding:10px;border:1px solid #e2e8f0;"><?php echo esc_html($resource->nivel_escolar); ?></td>
                                </tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Edad:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->age) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Nivel en Otros Países:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->level_other_countries) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Tipo de Archivo:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->file_type) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Formato Visual:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->visual_format) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Usuario Destinatario:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->target_user) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Habilidades y Competencias:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($resource->skills_competencies) . '</td></tr>
                            </table>
                            
                            <div style="background-color:#f8f9fa;border-left:4px solid #0093D0;padding:20px;border-radius:8px;margin-top:35px;">
                                <p style="color:#1A3D8F;font-size:14px;line-height:1.6;margin:0;">
                                    <strong>¿Necesitas ayuda?</strong><br>
                                    Si tienes preguntas sobre el rechazo de tu recurso o necesitas asistencia para mejorarlo,
                                    no dudes en contactarnos a través de nuestro correo: <a href="mailto:sirec@convenioandresbello.org" style="color:#0093D0;">sirec@convenioandresbello.org</a>
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
                
                <table role="presentation" style="max-width:600px;margin:20px auto;text-align:center;">
                    <tr>
                        <td style="padding:25px 0;">
                            <p style="color:#666666;font-size:12px;line-height:1.6;">
                                © ' . date('Y') . ' SIREC - Sistema de Recursos Educativos CAB<br>
                                Todos los derechos reservados<br>
                                <a href="https://convenioandresbello.org" style="color:#0093D0;text-decoration:none;">convenioandresbello.org</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </body>
            </html>';
            
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $email_sent = wp_mail($author_email, $subject, $message, $headers);
            
            if ($email_sent) {
                wp_send_json_success(array('message' => 'Recurso rechazado y notificación enviada exitosamente', 'email_sent' => true));
            } else {
                wp_send_json_success(array('message' => 'Recurso rechazado pero hubo un problema al enviar la notificación', 'email_sent' => false));
            }
        } else {
            wp_send_json_error('Error al rechazar el recurso');
        }
    }
    
    public function display_form() {
        ob_start();
        
        include ERM_PLUGIN_DIR . 'templates/forms/confirmation-modal.php';
        include ERM_PLUGIN_DIR . 'templates/forms/submission-form.php';
        
        return ob_get_clean();
    }

    public function handle_resource_update() {


        if (!check_ajax_referer('update_resource', 'nonce', false)) {

            wp_send_json_error('Invalid nonce');

            return;

        }

    


        if (!isset($_POST['resource_id']) || !isset($_POST['resource_data'])) {

            wp_send_json_error('Missing required data');

            return;

        }

    

        $resource_id = intval($_POST['resource_id']);

        $resource_data = $_POST['resource_data'];

    


        $sanitized_data = array();

        foreach ($resource_data as $key => $value) {

            if ($key === 'description') {

                $sanitized_data[$key] = sanitize_textarea_field($value);

            } else {

                $sanitized_data[$key] = sanitize_text_field($value);

            }

        }

    

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
        
        if (!isset($_POST['title']) || empty($_POST['title'])) {
            wp_send_json_error('Título es requerido');
            return;
        }

        $file_url = '';
        $file_path = '';
        $resource_link = '';
        if (!empty($_FILES['resource_file'])) {
            $uploaded_file = $_FILES['resource_file'];
            if ($uploaded_file['error'] === 0) {
                $upload_dir = wp_upload_dir();
                $file_name = sanitize_file_name($uploaded_file['name']);
                $file_path = $upload_dir['path'] . '/' . $file_name;
                $file_url = $upload_dir['url'] . '/' . $file_name;
                if (!move_uploaded_file($uploaded_file['tmp_name'], $file_path)) {
                    wp_send_json_error('Error al mover el archivo subido');
                    return;
                }
            } else {
                wp_send_json_error('Error en la subida del archivo');
                return;
            }
        }

        if (isset($_POST['resource_link']) && !empty($_POST['resource_link'])) {
            $resource_link = esc_url_raw($_POST['resource_link']);
        }

        // Procesar la portada (cover_image)
        $cover_image_url = '';
        $cover_image_path = '';
        if (!empty($_FILES['cover_image'])) {
            $cover_file = $_FILES['cover_image'];
            if ($cover_file['error'] === 0) {
                $upload_dir = wp_upload_dir();
                $cover_file_name = sanitize_file_name($cover_file['name']);
                $cover_image_path = $upload_dir['path'] . '/' . $cover_file_name;
                $cover_image_url = $upload_dir['url'] . '/' . $cover_file_name;
                if (!move_uploaded_file($cover_file['tmp_name'], $cover_image_path)) {
                    wp_send_json_error('Error al mover la imagen de portada');
                    return;
                }
            } else {
                wp_send_json_error('Error en la subida de la imagen de portada');
                return;
            }
        } else {
            wp_send_json_error('El campo Imagen/Portada es obligatorio');
            return;
        }

        $skills_competencies = isset($_POST['skills_competencies']) ? sanitize_text_field($_POST['skills_competencies']) : '';
        
        $data = array(
            'title'                          => sanitize_text_field($_POST['title']),
            'subtitle'                       => sanitize_text_field($_POST['subtitle']),
            'description'                    => sanitize_textarea_field($_POST['description']),
            'category'                       => sanitize_text_field($_POST['category']),
            'skills_competencies'            => $skills_competencies,
            'knowledge_area'                 => sanitize_text_field($_POST['knowledge_area']),
            'knowledge_area_other_countries' => sanitize_text_field($_POST['knowledge_area_other_countries']),
            'language'                       => sanitize_text_field($_POST['language']),
            'nivel_escolar'                  => sanitize_text_field($_POST['nivel_escolar']),
            'level_other_countries'          => sanitize_text_field($_POST['level_other_countries']),
            'age'                            => sanitize_text_field($_POST['age']),
            'target_user'                    => sanitize_text_field($_POST['target_user']),
            'license'                        => sanitize_text_field($_POST['license']),
            'author'                         => sanitize_text_field($_POST['author']),
            'author_email'                   => sanitize_email($_POST['author_email']),
            'country'                        => sanitize_text_field($_POST['country']),
            'origin'                         => sanitize_text_field($_POST['origin']),
            'publication_date'               => sanitize_text_field($_POST['publication_date']),
            'file_type'                      => sanitize_text_field($_POST['file_type']),
            'visual_format'                  => sanitize_text_field($_POST['visual_format']),
            'file_url'                       => $file_url,
            'file_path'                      => $file_path,
            'file_link'                      => ($resource_link !== '') ? $resource_link : '',
            'cover_image'                    => $cover_image_url,
            'submission_date'                => current_time('mysql')
        );
        
        $insert_result = $this->db->insert_resource($data);
        
        if ($insert_result) {
            // Correo para el autor
            $subject = 'Confirmación de envío de Recurso Educativo - SIREC';
            $message = '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style>
                    @media only screen and (max-width: 600px) {
                        .content-table td {display: block; width: 100% !important;}
                    }
                </style>
            </head>
            <body style="margin:0;padding:0;font-family:\'Montserrat\',Arial,sans-serif;background-color:#f8f9fa;">
                <table role="presentation" style="width:100%;border-collapse:collapse;background-color:#ffffff;">
                    <tr>
                        <td style="padding:30px 0;text-align:center;border-bottom:3px solid #0093D0;">
                            <img src="https://convenioandresbello.org/wp-content/uploads/2023/11/logo_CAB_2024.png" alt="Logo CAB" style="max-width:280px;height:auto;">
                        </td>
                    </tr>
                </table>
                
                <table role="presentation" style="max-width:600px;margin:30px auto;background-color:#ffffff;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="padding:40px 30px;">
                            <h1 style="color:#1A3D8F;font-size:26px;margin-bottom:25px;border-bottom:2px solid #0093D0;padding-bottom:15px;font-weight:600;">
                                Confirmación de Recepción de Recurso Educativo
                            </h1>
                            
                            <p style="color:#4a5568;font-size:16px;line-height:1.6;margin-bottom:30px;">
                                Estimado/a ' . esc_html($data['author']) . ',
                            </p>
                            
                            <div style="background-color:#f8f9fa;border-left:4px solid #0093D0;padding:20px;border-radius:8px;margin-bottom:30px;">
                                <p style="color:#1A3D8F;font-size:18px;font-weight:600;margin:0;">
                                    Hemos recibido exitosamente tu propuesta de recurso educativo.
                                </p>
                            </div>
                            
                            <h2 style="color:#1A3D8F;font-size:20px;margin-top:30px;">Detalles del Recurso Enviado:</h2>
                            <table style="width:100%;border-collapse:collapse;margin-top:15px;">
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Título:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['title']) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Subtítulo:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['subtitle']) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Categoría:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['category']) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Autor:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['author']) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Email:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['author_email']) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Procedencia:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['origin']) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">País:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['country']) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Área de Conocimiento:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['knowledge_area']) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Áreas en Otros Países:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['knowledge_area_other_countries']) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Descripción:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['description']) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Fecha de Publicación:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['publication_date']) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Idioma:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['language']) . '</td></tr>
                                <tr>
                                    <td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Nivel escolar:</td>
                                    <td style="padding:10px;border:1px solid #e2e8f0;"><?php echo esc_html($resource->nivel_escolar); ?></td>
                                </tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Edad:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['age']) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Nivel en Otros Países:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['level_other_countries']) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Tipo de Archivo:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['file_type']) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Formato Visual:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['visual_format']) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Usuario Destinatario:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['target_user']) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Habilidades y Competencias:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['skills_competencies']) . '</td></tr>
                                <tr><td style="padding:10px;background-color:#f8f9fa;border:1px solid #e2e8f0;font-weight:bold;">Licencia:</td><td style="padding:10px;border:1px solid #e2e8f0;">' . esc_html($data['license']) . '</td></tr>
                            </table>
                            
                            <div style="background-color:#f8f9fa;border-left:4px solid #0093D0;padding:20px;border-radius:8px;margin-top:35px;">
                                <p style="color:#1A3D8F;font-size:14px;line-height:1.6;margin:0;">
                                    <strong>Próximos pasos:</strong><br>
                                    Tu recurso será revisado por nuestro equipo. 
                                    Te notificaremos por correo electrónico cuando haya sido evaluado.
                                </p>
                            </div>
                            
                            <div style="background-color:#f8f9fa;border-left:4px solid #0093D0;padding:20px;border-radius:8px;margin-top:20px;">
                                <p style="color:#1A3D8F;font-size:14px;line-height:1.6;margin:0;">
                                    <strong>¿Necesitas ayuda?</strong><br>
                                    Si tienes preguntas sobre el estado de tu recurso o necesitas asistencia,
                                    no dudes en contactarnos a través de nuestro correo: <a href="mailto:sirec@convenioandresbello.org" style="color:#0093D0;">sirec@convenioandresbello.org</a>
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
                
                <table role="presentation" style="max-width:600px;margin:20px auto;text-align:center;">
                    <tr>
                        <td style="padding:25px 0;">
                            <p style="color:#666666;font-size:12px;line-height:1.6;">
                                © ' . date('Y') . ' SIREC - Sistema de Recursos Educativos CAB<br>
                                Todos los derechos reservados<br>
                                <a href="https://convenioandresbello.org" style="color:#0093D0;text-decoration:none;">convenioandresbello.org</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </body>
            </html>';
            
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $email_sent = wp_mail($data['author_email'], $subject, $message, $headers);
            
            // --- NUEVO: Enviar notificación a todos los usuarios con rol "catalogator" ---
            $catalogators = get_users( array( 'role' => 'catalogator' ) );
            $subject_catalogator = 'Nuevo recurso educativo enviado - SIREC';
            // Ejemplo: se envía un enlace a la sección del administrador para revisión
            $review_url = admin_url('admin.php?page=educational-resources');
            $message_catalogator = '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style>
                    @media only screen and (max-width: 600px) {
                        .content-table td {display: block; width: 100% !important;}
                    }
                </style>
            </head>
            <body style="margin:0;padding:0;font-family:&apos;Montserrat&apos;,Arial,sans-serif;background-color:#f8f9fa;">
                <table role="presentation" style="width:100%;border-collapse:collapse;background-color:#ffffff;">
                    <tr>
                        <td style="padding:30px 0;text-align:center;border-bottom:3px solid #0093D0;">
                            <img src="https://convenioandresbello.org/wp-content/uploads/2023/11/logo_CAB_2024.png" alt="Logo CAB" style="max-width:280px;height:auto;">
                        </td>
                    </tr>
                </table>
                
                <table role="presentation" style="max-width:600px;margin:30px auto;background-color:#ffffff;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="padding:40px 30px;">
                            <h1 style="color:#1A3D8F;font-size:26px;margin-bottom:25px;border-bottom:2px solid #0093D0;padding-bottom:15px;font-weight:600;">
                                Nuevo Recurso Educativo Recibido
                            </h1>
                            
                            <p style="color:#4a5568;font-size:16px;line-height:1.6;margin-bottom:30px;">
                                Se ha enviado un nuevo recurso educativo por <strong>' . esc_html($data['author']) . '</strong>.
                            </p>
                            
                            <p style="color:#4a5568;font-size:16px;line-height:1.6;margin-bottom:30px;">
                                Ingresa a <a href="' . esc_url($review_url) . '">este enlace</a> para revisarlo.
                            </p>
                            
                            <p style="color:#4a5568;font-size:16px;line-height:1.6;">
                                ¡Gracias!
                            </p>
                        </td>
                    </tr>
                </table>
                
                <table role="presentation" style="max-width:600px;margin:20px auto;text-align:center;">
                    <tr>
                        <td style="padding:25px 0;">
                            <p style="color:#666666;font-size:12px;line-height:1.6;">
                                © ' . date('Y') . ' SIREC - Sistema de Recursos Educativos CAB<br>
                                Todos los derechos reservados<br>
                                <a href="https://convenioandresbello.org" style="color:#0093D0;text-decoration:none;">convenioandresbello.org</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </body>
            </html>';
            
            // Enviar el correo a cada usuario catalogator
            foreach ($catalogators as $catalogator) {
                wp_mail($catalogator->user_email, $subject_catalogator, $message_catalogator, $headers);
            }
            
            if ($email_sent) {
                wp_send_json_success(array(
                    'message' => 'Recurso enviado exitosamente y notificaciones enviadas',
                    'email_sent' => true
                ));
            } else {
                wp_send_json_success(array(
                    'message' => 'Recurso enviado pero hubo un problema al enviar la notificación al autor',
                    'email_sent' => false
                ));
            }
        } else {
            wp_send_json_error('Error al guardar el recurso');
        }
    }
}