<?php
/**
 * Plugin Name: Educational Resources
 * Description: Plugin for managing educational resources with metadata
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: educational-resources
 */
// educational-resources.php

if (!defined('WPINC')) {
    die;
}

define('EDUCATIONAL_RESOURCES_VERSION', '1.0.0');
define('EDUCATIONAL_RESOURCES_PLUGIN_DIR', plugin_dir_path(__FILE__));

class Educational_Resources_Plugin {
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('init', array($this, 'init'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'public_enqueue_scripts'));
        add_shortcode('educational_resource_form', array($this, 'render_form'));
        register_activation_hook(__FILE__, array($this, 'activate'));
    }

    public function init() {
        $this->create_tables();
    }

    public function activate() {
        $this->create_tables();
        flush_rewrite_rules();
    }

    public function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}educational_resources (
            id bigint(20) NOT NULL AUTO_INCREMENT,
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
            status varchar(20) DEFAULT 'pending',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function add_admin_menu() {
        add_menu_page(
            'Educational Resources',
            'Educational Resources',
            'manage_options',
            'educational-resources',
            array($this, 'admin_page'),
            'dashicons-welcome-learn-more',
            30
        );
    }

    public function admin_page() {
        global $wpdb;
        $resources = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}educational_resources ORDER BY created_at DESC");
        include EDUCATIONAL_RESOURCES_PLUGIN_DIR . 'templates/admin-page.php';
    }

    public function render_form() {
        ob_start();
        include EDUCATIONAL_RESOURCES_PLUGIN_DIR . 'templates/submission-form.php';
        return ob_get_clean();
    }

    public function admin_enqueue_scripts() {
        wp_enqueue_style('educational-resources-admin', plugins_url('assets/css/admin.css', __FILE__));
        wp_enqueue_script('educational-resources-admin', plugins_url('assets/js/admin.js', __FILE__), array('jquery'), null, true);
    }

    public function public_enqueue_scripts() {
        wp_enqueue_style('educational-resources-public', plugins_url('assets/css/public.css', __FILE__));
        wp_enqueue_script('educational-resources-public', plugins_url('assets/js/public.js', __FILE__), array('jquery'), null, true);
    }

    public function process_form_submission() {
        if (!isset($_POST['educational_resource_nonce']) || 
            !wp_verify_nonce($_POST['educational_resource_nonce'], 'submit_educational_resource')) {
            return;
        }

        global $wpdb;
        
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
            'description' => wp_kses_post($_POST['description']),
            'publication_date' => sanitize_text_field($_POST['publication_date']),
            'last_update' => sanitize_text_field($_POST['last_update']),
            'language' => sanitize_text_field($_POST['language']),
            'school_sequence' => sanitize_text_field($_POST['school_sequence']),
            'level_other_countries' => sanitize_text_field($_POST['level_other_countries']),
            'file_type' => sanitize_text_field($_POST['file_type']),
            'visual_format' => sanitize_text_field($_POST['visual_format']),
            'target_user' => sanitize_text_field($_POST['target_user']),
            'skills' => sanitize_text_field($_POST['skills']),
            'license' => sanitize_text_field($_POST['license'])
        );

        $wpdb->insert($wpdb->prefix . 'educational_resources', $data);
    }
}

function Educational_Resources() {
    return Educational_Resources_Plugin::get_instance();
}

Educational_Resources();