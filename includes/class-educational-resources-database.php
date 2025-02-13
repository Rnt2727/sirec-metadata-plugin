<?php
class Educational_Resources_Database {
    private static $table_name = 'wp_educational_resources';
    
    public static function create_table() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS " . self::$table_name . " (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            title text NOT NULL,
            subtitle text,
            category varchar(100) NOT NULL,
            author varchar(100) NOT NULL,
            author_email varchar(100),
            origin varchar(100),
            country varchar(100),
            knowledge_area text,
            knowledge_area_other text,
            description longtext,
            publication_date date,
            last_update date,
            language varchar(50),
            school_sequence text,
            level_other_countries text,
            file_type varchar(50),
            visual_format varchar(100),
            target_user varchar(100),
            skills text,
            license varchar(100),
            cab_rating int,
            cab_seal varchar(50),
            submission_date datetime DEFAULT CURRENT_TIMESTAMP,
            status varchar(50) DEFAULT 'pending',
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}