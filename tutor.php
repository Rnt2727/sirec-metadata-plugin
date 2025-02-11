<?php
/**
 * Plugin Name: Metadata DSpace
 * Plugin URI: https://github.com/tuusuario/metadata-dspace
 * Description: Plugin para gestionar metadatos de DSpace en WordPress
 * Author: Tu Nombre
 * Version: 1.0.0
 * Author URI: https://tuwebsite.com
 * Requires PHP: 7.4
 * Requires at least: 5.3
 * License: GPLv2 or later
 * Text Domain: metadata-dspace
 *
 * @package MetadataDSpace
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Definir constantes del plugin
 */
define('METADATA_DSPACE_VERSION', '1.0.0');
define('METADATA_DSPACE_FILE', __FILE__);
define('METADATA_DSPACE_PATH', plugin_dir_path(__FILE__));
define('METADATA_DSPACE_URL', plugin_dir_url(__FILE__));

/**
 * Cargar traducciones
 */
add_action('init', function() {
    load_plugin_textdomain('metadata-dspace', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

/**
 * Clase principal del plugin
 */
class MetadataDSpace {
    
    private static $instance = null;

    /**
     * Constructor
     */
    public function __construct() {
        $this->define_constants();
        $this->load_dependencies();
        $this->init_hooks();
    }

    /**
     * Singleton
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Definir constantes adicionales
     */
    private function define_constants() {
        // Rutas a los directorios de estilos y vistas
        define('METADATA_DSPACE_VIEWS', METADATA_DSPACE_PATH . 'views/elements/');
        define('METADATA_DSPACE_STYLES', METADATA_DSPACE_PATH . 'includes/theme-compatibility/');
    }

    /**
     * Cargar dependencias
     */
    private function load_dependencies() {
        // Cargar estilos CSS
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        
        // Cargar templates
        require_once METADATA_DSPACE_VIEWS . 'bulk-confirm-popup.php';
        require_once METADATA_DSPACE_VIEWS . 'common-confirm-popup.php';
        require_once METADATA_DSPACE_VIEWS . 'filters.php';
        require_once METADATA_DSPACE_VIEWS . 'navbar.php';
        require_once METADATA_DSPACE_VIEWS . 'pagination.php';
        require_once METADATA_DSPACE_VIEWS . 'search-filter.php';
    }

    /**
     * Inicializar hooks
     */
    private function init_hooks() {
        // Activación
        register_activation_hook(METADATA_DSPACE_FILE, array($this, 'activate'));
        
        // Desactivación
        register_deactivation_hook(METADATA_DSPACE_FILE, array($this, 'deactivate'));
    }

    /**
     * Cargar estilos admin
     */
    public function enqueue_admin_styles() {
        // Cargar estilos de los diferentes temas
        wp_enqueue_style('metadata-dspace-astra', METADATA_DSPACE_STYLES . 'astra/assets/css/style.css');
        wp_enqueue_style('metadata-dspace-flatpro', METADATA_DSPACE_STYLES . 'flatpro/assets/css/style.css');
        wp_enqueue_style('metadata-dspace-storefront', METADATA_DSPACE_STYLES . 'storefront/assets/css/style.css');
    }

    /**
     * Activar plugin
     */
    public function activate() {
        // Código de activación
    }

    /**
     * Desactivar plugin
     */
    public function deactivate() {
        // Código de desactivación
    }
}

/**
 * Iniciar plugin
 */
function metadata_dspace() {
    return MetadataDSpace::instance();
}

$GLOBALS['metadata_dspace'] = metadata_dspace();