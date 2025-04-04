<?php
class ERM_DSpace {
    private $csrf = null;
    private $bearer = null;
    private $cookies = [];
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_dspace_menu'));
        add_action('admin_head', array($this, 'add_dspace_styles'));
        add_action('wp_ajax_upload_to_dspace', array($this, 'handle_ajax_upload'));
    }
    
    public function add_dspace_menu() {
        $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;
    
        $is_catalogator = in_array('catalogator', $user_roles);
        $is_evaluator = in_array('evaluator', $user_roles);
        $is_administrator = in_array('administrator', $user_roles);
    
        if ($is_catalogator || $is_administrator) {
			/*
            add_submenu_page(
                'educational-resources', 
                'DSpace Uploader', 
                'DSpace Uploader', 
                'read', 
                'dspace-uploader',
                array($this, 'display_dspace_page')
            );
			*/
        }
    }
    
    public function add_dspace_styles() {
        ?>
        <style>
            .dspace-result pre {
                background: #f5f5f5;
                padding: 10px;
                overflow: auto;
                border: 1px solid #ddd;
                border-radius: 3px;
            }
        </style>
        <?php
    }
    
    public function display_dspace_page() {
        $message = '';
        $message_type = '';
        
        if (isset($_POST['dspace_submit'])) {
            $title = sanitize_text_field($_POST['title']);
            $collection_id = sanitize_text_field($_POST['collection']);
            
            if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                $file_tmp = $_FILES['file']['tmp_name'];
                $file_name = $_FILES['file']['name'];
                
                $upload_dir = wp_upload_dir();
                $target_file = $upload_dir['path'] . '/' . $file_name;
                
                if (move_uploaded_file($file_tmp, $target_file)) {
                    $metadata = [
                        [
                            "op" => "add",
                            "path" => "/sections/traditionalpageone/dc.title",
                            "value" => [["value" => $title]]
                        ],
                        [
                            "op" => "add",
                            "path" => "/sections/traditionalpageone/dc.date.issued",
                            "value" => [["value" => date("Y")]]
                        ],
                        [
                            "op" => "add",
                            "path" => "/sections/license/granted",
                            "value" => true
                        ]
                    ];
                    
                    $result = $this->uploadItem($target_file, $metadata, $collection_id);
                    
                    $message = '<h3>Resultado de la subida:</h3><pre>' . $result . '</pre>';
                    $message_type = 'success';
                    
                    unlink($target_file);
                } else {
                    $message = 'Error al mover el archivo subido.';
                    $message_type = 'error';
                }
            } else {
                $message = 'Error al subir el archivo.';
                $message_type = 'error';
            }
        }
        
        ?>
        <div class="wrap">
            <h1>DSpace Uploader</h1>
            
            <?php if ($message): ?>
                <div class="notice notice-<?php echo $message_type; ?> is-dismissible">
                    <p><?php echo $message; ?></p>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <h2>Subir archivo a DSpace</h2>
                <form method="post" enctype="multipart/form-data">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="title">Título</label></th>
                            <td><input type="text" name="title" id="title" class="regular-text" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="file">Archivo</label></th>
                            <td><input type="file" name="file" id="file" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="collection">ID de Colección</label></th>
                            <td><input type="text" name="collection" id="collection" class="regular-text" value="4470bb28-256f-4ed1-bde9-f29ef135d22c"></td>
                        </tr>
                    </table>
                    
                    <?php submit_button('Subir a DSpace', 'primary', 'dspace_submit'); ?>
                </form>
            </div>
        </div>
        <?php
    }
    
    private function getCsrfAndBearer($responseHeaders) {
        $cookies = [];
    
        foreach ($responseHeaders as $header) {
            if (stripos($header, 'Authorization:') === 0) {
                $this->bearer = substr($header, 15);
            }
            if (stripos($header, 'Set-Cookie:') === 0) {
                $cookie = explode(';', substr($header, 12))[0];
                $cookies[] = $cookie;
    
                if (strpos($cookie, 'DSPACE-XSRF-COOKIE=') !== false) {
                    $this->csrf = str_replace('DSPACE-XSRF-COOKIE=', '', $cookie);
                }
            }
        }
        $this->cookies = $cookies;
    }
    
    private function makeRequestWithCookies($url, $method = "GET", $body = "", $contenttype = "application/json") {
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $headers = [];
        if ($this->csrf) {
            $headers[] = "X-XSRF-TOKEN: " . $this->csrf;
        }
        if ($this->bearer) {
            $headers[] = "Authorization: " . $this->bearer;
        }
    
        $headers[] = "Cookie: " . implode("; ", $this->cookies);
        $headers[] = "Content-Type: $contenttype";
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headersResponse = explode("\r\n", substr($response, 0, $headerSize));
        $body = substr($response, $headerSize);
    
        curl_close($ch);
        $this->getCsrfAndBearer($headersResponse);
    
        if ($httpcode > 299) {
            throw new Exception("Error en la solicitud: $httpcode - $body");
        }
    
        return json_decode($body, true);
    }
    
    private function getLoginStatus() {
        return $this->makeRequestWithCookies("https://backdspace.edupan.dev/server/api/authn/status");
    }
    
    private function iniciarSesion($usuario, $contrasena) {
        $this->getLoginStatus();
    
        $resultBody = $this->makeRequestWithCookies(
            "https://backdspace.edupan.dev/server/api/authn/login?user=$usuario&password=$contrasena", 
            "POST", 
            json_encode([
                "user" => $usuario,
                "password" => $contrasena
            ])
        );
    
        $this->getLoginStatus();
    }
    
    public function uploadItem($filename, $metadata, $collection_id, $realmetadata = null) {
        try {
            $this->iniciarSesion("giancarlo_olivares@edupan.com", "G@LD8(KrW9kEJZwF");
    
            $data = json_encode(["type" => "workspaceitem"]);
    
            $original_filename = basename($filename);

        // Determinar el tipo MIME del archivo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $filename);
        finfo_close($finfo);

        // Crear el objeto CURLFile con el nombre y tipo MIME correctos
        $file = new CURLFile($filename, $mime_type, $original_filename);
        $filedata = [
            "file" => $file
        ];
    
            $responseWorkspaceitem = $this->makeRequestWithCookies(
                "https://backdspace.edupan.dev/server/api/submission/workspaceitems?owningCollection=$collection_id", 
                "POST", 
                $data
            );
    
            $this->getLoginStatus();
    
            $workspaceid = $responseWorkspaceitem['id'];
            $itemid = $responseWorkspaceitem['_embedded']['item']["id"];

            if ($realmetadata) {
                $response = $this->makeRequestWithCookies(
                    "https://backdspace.edupan.dev/server/api/core/items/$itemid", 
                    "PATCH", 
                    json_encode($realmetadata)
                );
                
                $this->getLoginStatus();
            }

    
            $response = $this->makeRequestWithCookies(
                "https://backdspace.edupan.dev/server/api/submission/workspaceitems/$workspaceid", 
                "PATCH", 
                json_encode($metadata)
            );
    
            $this->getLoginStatus();
    
            $response = $this->makeRequestWithCookies(
                "https://backdspace.edupan.dev/server/api/submission/workspaceitems/$workspaceid", 
                "POST", 
                $filedata, 
                "multipart/form-data"
            );
    
            $this->getLoginStatus();
    
            $workspaceitemsUrl = "https://backdspace.edupan.dev/server/api/submission/workspaceitems/$workspaceid";
    
            $response = $this->makeRequestWithCookies(
                "https://backdspace.edupan.dev/server/api/workflow/workflowitems", 
                "POST", 
                $workspaceitemsUrl, 
                "text/uri-list"
            );
    
            return json_encode($response);
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }


    public function handle_ajax_upload() {

        $realmetadata = [];
        
        // Verificar nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'update_resource')) {
            wp_send_json_error('Verificación de seguridad fallida');
        }
        
        // Verificar permisos
        if (!current_user_can('administrator')) {
            wp_send_json_error('No tienes permisos para realizar esta acción');
        }
        
        // Obtener datos
        $resource_id = isset($_POST['resource_id']) ? intval($_POST['resource_id']) : 0;
        $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        $file_url = isset($_POST['file_url']) ? esc_url_raw($_POST['file_url']) : '';
        
        if (empty($resource_id) || empty($title) || empty($file_url)) {
            wp_send_json_error('Faltan datos requeridos');
        }
        
        // Obtener el recurso completo de la base de datos
        $db = new ERM_Database();
        $resource = $db->get_resource_by_id($resource_id);
        
        if (!$resource) {
            wp_send_json_error('Recurso no encontrado');
        }
        
        // Descargar el archivo a una ubicación temporal
        $temp_file = download_url($file_url);

        if (is_wp_error($temp_file)) {
            wp_send_json_error('Error al descargar el archivo: ' . $temp_file->get_error_message());
        }

        // Obtener la extensión original del archivo
        $file_info = pathinfo($file_url);
        $file_name = basename($file_url);
        $extension = isset($file_info['extension']) ? $file_info['extension'] : '';

        // Crear un nuevo nombre de archivo con la extensión correcta
        if (!empty($extension)) {
            $new_temp_file = $temp_file . '.' . $extension;
            rename($temp_file, $new_temp_file);
            $temp_file = $new_temp_file;
        }   
        
        // Preparar metadatos
        // Preparar metadatos básicos
$metadata = [
    [
        "op" => "add",
        "path" => "/sections/traditionalpageone/dc.title",
        "value" => [["value" => $title]]
    ],
    [
        "op" => "add",
        "path" => "/sections/license/granted",
        "value" => true
    ]
];

$metadata[] = [
    "op" => "add",
    "path" => "/sections/traditionalpageone/dc.date.issued",
    "value" => [["value" => date("Y-m-d")]]
];

// Añadir subtítulo (campo 2)
if (!empty($resource->subtitle)) {
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.subtitle/-",
        "value" => [["value" => $resource->subtitle, "language" => null]]
    ];
}

$collection_id = "";

if (!empty($resource->category)) {
    // Mapear categorías a IDs de colección en DSpace
    $collection_id_map = [
        'RC' => 'b92ca822-2c62-4572-9bac-39b1eae5f4fe',    // RECURSOS DE CONSULTA
        'RAD' => 'e38ca326-4e59-46cf-af1f-96ce372d6f2c',   // RECURSOS DE APOYO PARA DOCENTES
        'RDC' => '4470bb28-256f-4ed1-bde9-f29ef135d22c',   // RECURSOS DIDACTICOS COMPLEMENTARIOS
        'RL' => '7b19a401-10db-4d2a-becb-3aec3cb2ff36',    // RECURSOS LUDICOS
        'RTE' => 'c5bfe954-4571-4b32-97cc-1dbb8775f623'    // RECURSO DE TRABAJO PARA ESTUDIANTES
    ];
    
    // Mapear códigos a nombres completos
    $category_name_map = [
        'RC' => 'Recursos de consulta',
        'RAD' => 'Recursos de apoyo para docentes',
        'RDC' => 'Recursos didácticos complementarios',
        'RL' => 'Recursos lúdicos',
        'RTE' => 'Recursos de trabajo para el estudiante'
    ];
    
    if (array_key_exists($resource->category, $collection_id_map)) {
        $collection_id = $collection_id_map[$resource->category];
    }

    $category_full_name = isset($category_name_map[$resource->category]) 
        ? $category_name_map[$resource->category] 
        : $resource->category;

        $realmetadata[] = [
            "op" => "add",
            "path" => "/metadata/dc.category/-",
            "value" => [["value" => $category_full_name, "language" => null]]
        ];
}

// Añadir autor (campo 4)
if (!empty($resource->author)) {
    $metadata[] = [
        "op" => "add",
        "path" => "/sections/traditionalpageone/dc.contributor.author",
        "value" => [["value" => $resource->author]]
    ];
}

// Añadir email del autor (campo 5)
if (!empty($resource->author_email)) {
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.author_email/-",
        "value" => [["value" => $author_email, "language" => null]]
    ];
}

if (!empty($resource->origin)) {
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.origin/-",
        "value" => [["value" => $origin, "language" => null]]
    ];
}

// Añadir país (campo 7)
if (!empty($resource->country)) {
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.country/-",
        "value" => [["value" => $country, "language" => null]]
    ];
}

// Añadir área de conocimiento (campo 8)
if (!empty($resource->knowledge_area)) {
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.knowledge_area/-",
        "value" => [["value" => $resource->knowledge_area, "language" => null]]
    ];
}


// // Añadir áreas de conocimiento en otros países (campo 9)
 if (!empty($resource->knowledge_area_other)) {
     // Dividir por comas ya que es un campo de tags
     $areas = explode(',', $resource->knowledge_area_other);
     $area_values = [];
   
     foreach ($areas as $area) {
         $area_values[] = ["value" => trim($area)];
     }
    
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.knowledge_area_other/-",
        "value" => [["value" => $resource->knowledge_area_other, "language" => null]]
    ];
 }

// // Añadir descripción (campo 10)
if (!empty($resource->description)) {
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.description/-",
        "value" => [["value" => $resource->description, "language" => null]]
    ];
 }
 

 // Añadir fecha de publicación (campo 11)
if (!empty($resource->publication_date)) {
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.date_published/-",
        "value" => [["value" => $resource->publication_date, "language" => null]]
    ];
}

// Añadir idioma (campo 12)
if (!empty($resource->language)) {
    // Dividir por comas ya que es un campo de tags
    $languages = explode(',', $resource->language);
    $language_values = [];
    
    foreach ($languages as $lang) {
        $language_values[] = ["value" => trim($lang)];
    }
    
    $metadata[] = [
        "op" => "add",
        "path" => "/sections/traditionalpageone/dc.language.iso",
        "value" => $language_values
    ];
}


 // Añadir secuencia escolar (campo 13) 
 if (!empty($resource->school_sequence)) {
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.school_sequence/-",
        "value" => [["value" => $resource->school_sequence, "language" => null]]
    ];
 }

 // Añadir edad (campo 13 - parte 2)
 if (!empty($resource->age)) {
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.age/-",
        "value" => [["value" => $resource->age, "language" => null]]
    ];
 }

 // Añadir nivel en otros países (campo 14)
 if (!empty($resource->level_other_countries)) {
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.level_other_countries/-",
        "value" => [["value" => $resource->level_other_countries, "language" => null]]
    ];
}

// Añadir tipo de archivo (campo 15)
 if (!empty($resource->file_type)) {
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.file_type/-",
        "value" => [["value" => $resource->file_type, "language" => null]]
    ];
 }

 if (!empty($resource->file_link)) {
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.file_link/-",
        "value" => [["value" => $resource->file_link, "language" => null]]
    ];
}

// Añadir licencia (campo 17)
 if (!empty($resource->license)) {
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.rights.license/-",
        "value" => [["value" => $resource->license, "language" => null]]
    ];
}

// Añadir formato visual (campo 18)
if (!empty($resource->visual_format)) {
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.visual_format/-",
        "value" => [["value" => $resource->visual_format, "language" => null]]
    ];
}

// // Añadir usuario destinatario (campo 19)
if (!empty($resource->target_user)) {
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.target_user/-",
        "value" => [["value" => $resource->target_user, "language" => null]]
    ];
}

// // Añadir habilidades y competencias (campo 20)
if (!empty($resource->skills_competencies)) {
    // Dividir por comas ya que es un campo de checkboxes
    $skills = explode(',', $resource->skills_competencies);
    $skill_values = [];
    
    foreach ($skills as $skill) {
        $skill_values[] = ["value" => trim($skill)];
    }
    
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.skills_competencies/-",
        "value" => [["value" => $resource->skills_competencies, "language" => null]]
    ];
}

// // Añadir sello CAB si existe
if (isset($resource->cab_seal) && $resource->cab_seal) {
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.cab_seal/-",
        "value" => [["value" => $resource->cab_seal, "language" => null]]
    ];
}

// // Añadir puntuación CAB si existe
if (isset($resource->cab_score) && $resource->cab_score) {
    $realmetadata[] = [
        "op" => "add",
        "path" => "/metadata/dc.cab_score/-",
        "value" => [["value" => $resource->cab_score, "language" => null]]
    ];
}

        // $collection_id = "4470bb28-256f-4ed1-bde9-f29ef135d22c";
        
        // Subir a DSpace
        $result = $this->uploadItem($temp_file, $metadata, $collection_id, $realmetadata);
        
        // Eliminar archivo temporal
        @unlink($temp_file);
        
        // Devolver resultado
        if (strpos($result, 'Error') !== false) {
            wp_send_json_error($result);
        } else {  
            wp_send_json_success($result);
        }
    }
}