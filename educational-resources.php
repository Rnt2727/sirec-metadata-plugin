<?php

/*
Plugin Name: SIREC Metadata Plugin
Plugin URI: 
Description: Plugin para gestionar recursos educativos y sus metadatos
Version: 2.0
Author: SIREC
Author URI: 
License: GPL v2 or later
Text Domain: sirec-metadata
*/

if (!defined('ABSPATH')) {
    exit; 
}

define('ERM_VERSION', '2.0');
define('ERM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ERM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Asegúrate que el directorio includes existe
if (!file_exists(ERM_PLUGIN_DIR . 'includes')) {
    mkdir(ERM_PLUGIN_DIR . 'includes', 0755, true);
}

spl_autoload_register(function($class) {
    $prefix = 'ERM_';
    if (strpos($class, $prefix) !== 0) {
        return;
    }
    
    $class_name = str_replace($prefix, '', $class);
    $file = ERM_PLUGIN_DIR . 'includes/class-erm-' . 
            strtolower(str_replace('_', '-', $class_name)) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

class Educational_Resources_Manager {
    private static $instance = null;
    private $database;
    private $admin;
    private $form_handler;
    private $evaluator;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init();
    }
    
    private function init() {
        // Asegúrate que todas las clases necesarias estén disponibles
        if (!class_exists('ERM_Database')) {
            require_once ERM_PLUGIN_DIR . 'includes/class-erm-database.php';
        }
        if (!class_exists('ERM_Admin')) {
            require_once ERM_PLUGIN_DIR . 'includes/class-erm-admin.php';
        }
        if (!class_exists('ERM_Form_Handler')) {
            require_once ERM_PLUGIN_DIR . 'includes/class-erm-form-handler.php';
        }
        if (!class_exists('ERM_Evaluator')) {
            require_once ERM_PLUGIN_DIR . 'includes/class-erm-evaluator.php';
        }

        $this->database = new ERM_Database();
        $this->admin = new ERM_Admin();
        $this->form_handler = new ERM_Form_Handler();
        $this->evaluator = new ERM_Evaluator();
        
        register_activation_hook(__FILE__, array($this->database, 'create_tables'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_style('erm-styles', ERM_PLUGIN_URL . 'assets/css/style.css');
        wp_enqueue_script('erm-scripts', ERM_PLUGIN_URL . 'assets/js/script.js', array('jquery'));
        
        // Agregar el objeto ajax_object globalmente
        wp_localize_script('jquery', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('update_resource')
        ));
    }
}

function Educational_Resources_Manager() {
    return Educational_Resources_Manager::get_instance();
}

$GLOBALS['educational_resources_manager'] = Educational_Resources_Manager();