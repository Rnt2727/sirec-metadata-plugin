<?php
class ERM_Database {
    private $wpdb;
    private $table_name;
    
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'educational_resources';
        
        // Verificar y crear la tabla si no existe
        $this->ensure_table_exists();
    }

    private function ensure_table_exists() {
        try {
            if ($this->wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") != $this->table_name) {
                self::create_tables();
            }
        } catch (Exception $e) {
            error_log('Error al verificar/crear la tabla: ' . $e->getMessage());
            return false;
        }
        return true;
    }
    
    public function update_resource($id, $data) {
        $formats = array();
        foreach ($data as $key => $value) {
            if ($key === 'evaluation_score') {
                $formats[] = '%f';
            } else {
                $formats[] = '%s';
            }
        }
        
        return $this->wpdb->update(
            $this->table_name,
            $data,
            array('id' => $id),
            $formats,
            array('%d')
        );
    }

    public function update_evaluation_score($resource_id, $score) {
        return $this->wpdb->update(
            $this->table_name,
            array('evaluation_score' => $score),
            array('id' => $resource_id),
            array('%f'),
            array('%d')
        );
    }

    public function get_approved_resources() {
        try {
            $results = $this->wpdb->get_results(
                "SELECT * FROM {$this->table_name} 
                 WHERE approved_by_catalogator = 1 
                 ORDER BY id DESC"
            );
            return is_array($results) ? $results : array();
        } catch (Exception $e) {
            error_log('Error en get_approved_resources: ' . $e->getMessage());
            return array();
        }
    }

    public function get_resource_by_id($id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $id)
        );
    }
    
    public static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
    
        // La tabla incluye exactamente los campos requeridos:
        //  1. title (Título) [OBLIGATORIO]
        //  2. subtitle (Subtítulo) [OPCIONAL]
        //  3. description (Descripción) [OBLIGATORIO]
        //  4. category (Categoría) [OBLIGATORIO]
        //  5. skills_competencies (Habilidades/Competencias principales) [OBLIGATORIO]
        //  6. knowledge_area (Área de Conocimiento) [OBLIGATORIO]
        //  7. knowledge_area_other_countries (Área de Conocimiento en otros países) [OPCIONAL]
        //  8. language (Idioma del RE) [OBLIGATORIO]
        //  9. nivel_escolar (Nivel escolar) [OBLIGATORIO]
        // 10. level_other_countries (Denominación de Nivel en otros Países) [OPCIONAL]
        // 11. age (Edad) [OBLIGATORIO]
        // 12. target_user (Usuario al que está dirigido) [OBLIGATORIO]
        // 13. license (Licencia) [OBLIGATORIO]
        // 14. author (Autor/Autores) [OBLIGATORIO]
        // 15. author_email (Correo del Autor) [OBLIGATORIO]
        // 16. country (País) [OBLIGATORIO]
        // 17. origin (Procedencia/Lugar de Públicación) [OPCIONAL]
        // 18. publication_date (Fecha de Publicación) [OBLIGATORIO]
        // 19. file_type (Tipo de Archivo) [OBLIGATORIO]
        // 20. visual_format (Formato Visual) [OPCIONAL]
        // 21. file_link (Carga de RE/Enlace) [OBLIGATORIO]
        // 22. cover_image (Imagen/Portada) [OBLIGATORIO]
        // Además se incluyen campos extra (file_url, file_path, cab_rating, cab_seal, approved_by_catalogator, rejection_reason, evaluation_score, submission_date) para el funcionamiento interno.
        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}educational_resources (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            title text NOT NULL,
            subtitle text,
            description longtext NOT NULL,
            category varchar(100) NOT NULL,
            skills_competencies longtext NOT NULL,
            knowledge_area text NOT NULL,
            knowledge_area_other_countries text,
            language varchar(50) NOT NULL,
            nivel_escolar text NOT NULL,
            level_other_countries text,
            age varchar(10) NOT NULL,
            target_user text NOT NULL,
            license varchar(100) NOT NULL,
            author varchar(100) NOT NULL,
            author_email varchar(100) NOT NULL,
            country varchar(100) NOT NULL,
            origin varchar(100),
            publication_date date NOT NULL,
            file_type varchar(50) NOT NULL,
            visual_format varchar(100),
            file_url varchar(255),
            file_path varchar(255),
            file_link text NOT NULL,
            cover_image varchar(255) NOT NULL,
            cab_rating varchar(50),
            cab_seal varchar(50),
            approved_by_catalogator tinyint(1) DEFAULT NULL,
            rejection_reason text,
            evaluation_score decimal(5,2) DEFAULT NULL,
            submission_date datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";
    
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    public function insert_resource($data) {
        return $this->wpdb->insert($this->table_name, $data);
    }
    
    public function get_resources() {
        if (!$this->ensure_table_exists()) {
            return array();
        }
        try {
            $results = $this->wpdb->get_results("SELECT * FROM {$this->table_name} ORDER BY id DESC");
            return is_array($results) ? $results : array();
        } catch (Exception $e) {
            error_log('Error en get_resources: ' . $e->getMessage());
            return array();
        }
    }

    public function get_resources_with_min_score($min_score) {
        try {
            $results = $this->wpdb->get_results(
                $this->wpdb->prepare(
                    "SELECT * FROM {$this->table_name} 
                     WHERE evaluation_score >= %f 
                     ORDER BY evaluation_score DESC",
                    $min_score
                )
            );
            return is_array($results) ? $results : array();
        } catch (Exception $e) {
            error_log('Error en get_resources_with_min_score: ' . $e->getMessage());
            return array();
        }
    }

    public static function drop_tables() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'educational_resources';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }
}