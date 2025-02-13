<?php
/*
Plugin Name: Educational Resources Manager
Description: Plugin to manage educational resources with metadata
Version: 1.0
Author: Your Name
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Activation hook
register_activation_hook(__FILE__, 'erm_create_tables');

function erm_create_tables() {
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
        knowledge_area_other text,
        description longtext,
        publication_date date,
        last_update date,
        language varchar(50),
        school_sequence text,
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

// Add admin menu
add_action('admin_menu', 'erm_admin_menu');

function erm_admin_menu() {
    add_menu_page(
        'Educational Resources',
        'Educational Resources',
        'manage_options',
        'educational-resources',
        'erm_admin_page',
        'dashicons-welcome-learn-more'
    );
}

// Admin page display
function erm_admin_page() {
    global $wpdb;
    $resources = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}educational_resources ORDER BY submission_date DESC");
    ?>
    <div class="wrap">
        <h1>Educational Resources</h1>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Submission Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resources as $resource): ?>
                    <tr>
                        <td><?php echo esc_html($resource->title); ?></td>
                        <td><?php echo esc_html($resource->author); ?></td>
                        <td><?php echo esc_html($resource->category); ?></td>
                        <td><?php echo esc_html($resource->submission_date); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Shortcode for the submission form
add_shortcode('educational_resource_form', 'erm_display_form');

function erm_display_form() {
    ob_start();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_resource'])) {
        handle_form_submission();
    }
    ?>
    <form method="post" class="educational-resource-form">
        <div class="form-group">
            <label>Title *</label>
            <input type="text" name="title" required>
        </div>

        <div class="form-group">
            <label>Subtitle</label>
            <input type="text" name="subtitle">
        </div>

        <div class="form-group">
            <label>Category *</label>
            <select name="category" required>
                <option value="">Select Category</option>
                <option value="consultation">Resource for Consultation</option>
                <option value="teacher_support">Teacher Support Resource</option>
                <option value="complementary">Complementary Didactic Resource</option>
                <option value="ludic">Ludic Resource</option>
                <option value="student_work">Student Work Resource</option>
            </select>
        </div>

        <div class="form-group">
            <label>Author *</label>
            <input type="text" name="author" required>
        </div>

        <div class="form-group">
            <label>Author Email *</label>
            <input type="email" name="author_email" required>
        </div>

        <!-- Add all other fields following the same pattern -->

        <button type="submit" name="submit_resource">Submit Resource</button>
    </form>
    <?php
    return ob_get_clean();
}

function handle_form_submission() {
    global $wpdb;

    $data = array(
        'title' => sanitize_text_field($_POST['title']),
        'subtitle' => sanitize_text_field($_POST['subtitle']),
        'category' => sanitize_text_field($_POST['category']),
        'author' => sanitize_text_field($_POST['author']),
        'author_email' => sanitize_email($_POST['author_email']),
        // Add all other fields here
    );

    $wpdb->insert("{$wpdb->prefix}educational_resources", $data);

    if ($wpdb->insert_id) {
        echo '<div class="notice notice-success"><p>Resource submitted successfully!</p></div>';
    } else {
        echo '<div class="notice notice-error"><p>Error submitting resource.</p></div>';
    }
}

// Add basic styles
add_action('wp_enqueue_scripts', 'erm_enqueue_styles');

function erm_enqueue_styles() {
    wp_enqueue_style('erm-styles', plugins_url('css/style.css', __FILE__));
}