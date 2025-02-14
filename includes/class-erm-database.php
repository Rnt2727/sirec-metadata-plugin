<?php
class ERM_Database {
    private $wpdb;
    private $table_name;
    
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'educational_resources';
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
            age int,
            level_other_countries text,
            file_type varchar(50),
            visual_format varchar(100),
            target_user text,
            skills_competencies text,
            license text,
            cab_rating varchar(50),
            cab_seal varchar(50),
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
        return $this->wpdb->get_results("SELECT * FROM {$this->table_name} ORDER BY id DESC");
    }

    public static function drop_tables() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'educational_resources';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }
}