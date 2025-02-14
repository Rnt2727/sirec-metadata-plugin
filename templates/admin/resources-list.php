<?php
$db = new ERM_Database();
$resources = $db->get_resources();
?>
<div class="wrap">
    <h1>Recursos Educativos</h1>
    
    <div class="tablenav top">
        <div class="alignleft actions">
            <label for="filter-by-category" class="screen-reader-text">Filtrar por categoría</label>
            <select name="filter-by-category" id="filter-by-category">
                <option value="">Todas las categorías</option>
                <option value="RC">R.C. Recursos de consulta</option>
                <option value="RAD">R.A.D. Recursos de Apoyo para Docentes</option>
                <option value="RDC">R.D.C. Recursos Didácticos Complementarios</option>
                <option value="RL">R.L. Recursos Lúdicos</option>
                <option value="RTE">R.T.E. Recursos de Trabajo para el Estudiante</option>
            </select>
            <input type="submit" name="filter_action" id="post-query-submit" class="button" value="Filtrar">
        </div>
        <div class="tablenav-pages">
            <span class="displaying-num"><?php echo count($resources); ?> elementos</span>
        </div>
    </div>
    
    <table class="wp-list-table widefat fixed striped table-view-list">
        <thead>
            <tr>
                <th scope="col" class="manage-column">ID</th>
                <th scope="col" class="manage-column">Título</th>
                <th scope="col" class="manage-column">Subtítulo</th>
                <th scope="col" class="manage-column">Categoría</th>
                <th scope="col" class="manage-column">Autor</th>
                <th scope="col" class="manage-column">Email del Autor</th>
                <th scope="col" class="manage-column">Procedencia</th>
                <th scope="col" class="manage-column">País</th>
                <th scope="col" class="manage-column">Área de Conocimiento</th>
                <th scope="col" class="manage-column">Áreas en Otros Países</th>
                <th scope="col" class="manage-column">Descripción</th>
                <th scope="col" class="manage-column">Fecha de Publicación</th>
                <th scope="col" class="manage-column">Idioma</th>
                <th scope="col" class="manage-column">Secuencia Escolar</th>
                <th scope="col" class="manage-column">Nivel en Otros Países</th>
                <th scope="col" class="manage-column">Tipo de Archivo</th>
                <th scope="col" class="manage-column">Formato Visual</th>
                <th scope="col" class="manage-column">Usuario Destinatario</th>
                <th scope="col" class="manage-column">Habilidades y Competencias</th>
                <th scope="col" class="manage-column">Licencia</th>
                <th scope="col" class="manage-column">Calificación CAB</th>
                <th scope="col" class="manage-column">Sello CAB</th>
            </tr>
        </thead>
        <tbody>
            <?php if($resources && count($resources) > 0): ?>
                <?php foreach ($resources as $resource): ?>
                    <tr>
                        <td><?php echo esc_html($resource->id); ?></td>
                        <td><?php echo esc_html($resource->title); ?></td>
                        <td><?php echo esc_html($resource->subtitle); ?></td>
                        <td><?php echo esc_html($resource->category); ?></td>
                        <td><?php echo esc_html($resource->author); ?></td>
                        <td><?php echo esc_html($resource->author_email); ?></td>
                        <td><?php echo esc_html($resource->origin); ?></td>
                        <td><?php echo esc_html($resource->country); ?></td>
                        <td><?php echo esc_html($resource->knowledge_area); ?></td>
                        <td><?php echo esc_html($resource->knowledge_area_other_countries); ?></td>
                        <td>
                            <div class="description-content">
                                <?php echo esc_html($resource->description); ?>
                            </div>
                            <button type="button" class="toggle-description">Mostrar más</button>
                        </td>
                        <td><?php echo esc_html($resource->publication_date); ?></td>
                        <td><?php echo esc_html($resource->language); ?></td>
                        <td><?php echo esc_html($resource->school_sequence); ?></td>
                        <td><?php echo esc_html($resource->level_other_countries); ?></td>
                        <td><?php echo esc_html($resource->file_type); ?></td>
                        <td><?php echo esc_html($resource->visual_format); ?></td>
                        <td><?php echo esc_html($resource->target_user); ?></td>
                        <td><?php echo esc_html($resource->skills_competencies); ?></td>
                        <td><?php echo esc_html($resource->license); ?></td>
                        <td><?php echo esc_html($resource->cab_rating); ?></td>
                        <td><?php echo esc_html($resource->cab_seal); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="22">No hay recursos disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
    .wp-list-table th, .wp-list-table td {
        padding: 8px 10px;
        vertical-align: top;
    }
    
    .description-content {
        max-height: 100px;
        overflow: hidden;
        position: relative;
    }
    
    .description-content.expanded {
        max-height: none;
    }
    
    .toggle-description {
        background: none;
        border: none;
        color: #0073aa;
        cursor: pointer;
        padding: 0;
        text-decoration: underline;
    }
    
    .toggle-description:hover {
        color: #00a0d2;
    }
    
    @media screen and (max-width: 782px) {
        .wp-list-table th, .wp-list-table td {
            display: block;
            width: 100%;
        }
        
        .wp-list-table th {
            background-color: #f1f1f1;
            font-weight: bold;
        }
        
        .wp-list-table td {
            margin-bottom: 10px;
        }
        
        .wp-list-table td::before {
            content: attr(data-colname);
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
    }
</style>

<script>
jQuery(document).ready(function($) {
    $('.toggle-description').on('click', function() {
        var descriptionContent = $(this).prev('.description-content');
        descriptionContent.toggleClass('expanded');
        $(this).text(descriptionContent.hasClass('expanded') ? 'Mostrar menos' : 'Mostrar más');
    });
});
</script>