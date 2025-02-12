<?php
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Sirec_List_Table extends WP_List_Table {
    
    function __construct() {
        parent::__construct(array(
            'singular' => 'recurso',
            'plural'   => 'recursos',
            'ajax'     => false
        ));
    }

    function get_columns() {
        return array(
            'cb'                => '<input type="checkbox" />',
            'titulo'            => 'Título',
            'categoria'         => 'Categoría',
            'autor'             => 'Autor',
            'pais'              => 'País',
            'fecha_publicacion' => 'Fecha Publicación',
            'estado_revision'   => 'Estado',
            'valoracion_cab'    => 'Valoración CAB',
            'sello_cab'         => 'Sello CAB'
        );
    }

    function get_sortable_columns() {
        return array(
            'titulo'            => array('titulo', true),
            'categoria'         => array('categoria', false),
            'fecha_publicacion' => array('fecha_publicacion', false),
            'valoracion_cab'    => array('valoracion_cab', false)
        );
    }

    function column_default($item, $column_name) {
        return $item[$column_name];
    }

    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="recursos[]" value="%s" />', $item['id']
        );
    }

    function column_titulo($item) {
        $actions = array(
            'edit'   => sprintf('<a href="?page=sirec-recursos&action=edit&recurso=%s">Editar</a>', $item['id']),
            'delete' => sprintf('<a href="?page=sirec-recursos&action=delete&recurso=%s">Eliminar</a>', $item['id'])
        );

        return sprintf('%1$s %2$s', $item['titulo'], $this->row_actions($actions));
    }

    function prepare_items() {
        global $wpdb;
        $tabla = $wpdb->prefix . 'sirec_recursos';

        // Columnas
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        // Paginación
        $per_page = 20;
        $current_page = $this->get_pagenum();
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $tabla");

        // Ordenamiento
        $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'fecha_creacion';
        $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc';

        // Obtener datos
        $this->items = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $tabla ORDER BY %s %s LIMIT %d OFFSET %d",
                $orderby,
                $order,
                $per_page,
                ($current_page - 1) * $per_page
            ),
            ARRAY_A
        );

        // Configurar paginación
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }
}