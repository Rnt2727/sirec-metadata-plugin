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
            if($this->wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") != $this->table_name) {
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
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id = %d",
                $id
            )
        );
    }
    

    public static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}educational_resources (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            title text NOT NULL,
            subtitle text,
            category varchar(100),
            author varchar(100),
            author_email varchar(100),
            origin varchar(100),
            country varchar(100),
            knowledge_area text,
            knowledge_area_other_countries text,
            description longtext,
            publication_date date,
            last_update date,
            language varchar(50),
            school_sequence text,
            age varchar(10),
            level_other_countries text,
            file_type varchar(50),
            file_url varchar(255),        
            file_path varchar(255), 
            visual_format varchar(100),
            target_user text,
            skills_competencies LONGTEXT,
            license varchar(100),
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