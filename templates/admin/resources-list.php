<?php
$db = new ERM_Database();
$resources = $db->get_resources();
?>

<!-- Include Tutor LMS styles -->
<link rel="stylesheet" href="<?php echo plugins_url('tutor/assets/css/tutor.min.css'); ?>">

<style>
:root {
    --tutor-primary-color: #3E64DE;
    --tutor-primary-hover-color: #395BCA;
    --tutor-text-color: #212327;
    --tutor-border-color: #E3E5EB;
    --tutor-table-border: #E3E5EB;
    --tutor-body-color: #5B616F;
}

.tutor-table {
    width: 100%;
    font-size: 14px;
    border-collapse: separate;
    border-spacing: 0;
    background: #fff;
    border: 1px solid var(--tutor-table-border);
    border-radius: 4px;
}

.tutor-table th {
    font-weight: 500;
    background: #F7F9FA;
    padding: 15px 20px;
    border-bottom: 1px solid var(--tutor-table-border);
    color: var(--tutor-text-color);
    text-align: left;
}

.tutor-table td {
    padding: 15px 20px;
    border-bottom: 1px solid var(--tutor-table-border);
    color: var(--tutor-body-color);
}

.tutor-table tr:last-child td {
    border-bottom: none;
}

.tutor-badge-outline-primary {
    color: var(--tutor-primary-color);
    background: rgba(62, 100, 222, 0.1);
    border: 1px solid var(--tutor-primary-color);
    padding: 4px 8px;
    font-size: 12px;
    border-radius: 4px;
    display: inline-block;
}

.tutor-btn {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all 0.3s ease;
}

.tutor-btn-outline-primary {
    color: var(--tutor-primary-color);
    border-color: var(--tutor-primary-color);
    background: transparent;
}

.tutor-btn-outline-primary:hover {
    color: #fff;
    background: var(--tutor-primary-hover-color);
    border-color: var(--tutor-primary-hover-color);
}

.tutor-btn-sm {
    padding: 4px 12px;
    font-size: 13px;
}

.tutor-form-select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid var(--tutor-border-color);
    border-radius: 4px;
    background-color: #fff;
    font-size: 14px;
    color: var(--tutor-text-color);
}

.description-content {
    max-height: 50px;
    overflow: hidden;
    position: relative;
}

.description-content.expanded {
    max-height: none;
}

.toggle-description {
    margin-top: 5px;
    font-size: 12px !important;
    padding: 2px 8px !important;
}

@media (max-width: 768px) {
    .tutor-table-responsive {
        overflow-x: auto;
    }
    
    .tutor-table th,
    .tutor-table td {
        min-width: 200px;
    }
}
.table-outer-wrapper {
    position: relative;
    width: 100%;
    overflow: hidden;
    background: #fff;
    border-radius: 4px;
    border: 1px solid var(--tutor-table-border);
}

.tutor-table-responsive {
    position: relative;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    margin: 0;
    border: none;
}

.tutor-table {
    margin: 0;
    border: none;
    min-width: 100%;
    width: max-content;
}

/* Personalizar la barra de desplazamiento */
.tutor-table-responsive::-webkit-scrollbar {
    height: 8px;
}

.tutor-table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.tutor-table-responsive::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.tutor-table-responsive::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Asegurar que las columnas tengan un ancho mínimo */
.tutor-table th,
.tutor-table td {
    white-space: nowrap;
    min-width: 120px;
}

/* Ajustar el ancho de la columna de descripción */
.tutor-table th[width="15%"],
.tutor-table td:nth-child(11) {
    min-width: 200px;
    max-width: 300px;
    white-space: normal;
}

/* Mantener la columna de acciones visible */
.tutor-table td:last-child,
.tutor-table th:last-child {
    position: sticky;
    right: 0;
    background: inherit;
    border-left: 1px solid var(--tutor-table-border);
}

.tutor-table th:last-child {
    background: #F7F9FA;
}

.tutor-table td:last-child {
    background: #fff;
}

@media (max-width: 768px) {
    .table-outer-wrapper {
        margin: 0 -20px;
        width: calc(100% + 40px);
        border-left: none;
        border-right: none;
        border-radius: 0;
    }
    
    .tutor-table {
        min-width: 1500px;
    }
}
</style>

<div class="tutor-wrap tutor-dashboard tutor-dashboard-content">
    <div class="tutor-fs-5 tutor-fw-medium tutor-color-black tutor-mb-24">
        <?php esc_html_e('Recursos Educativos', 'tutor'); ?>
    </div>

    <!-- Filter Section -->
    <div class="tutor-row tutor-mb-32">
        <div class="tutor-col-lg-6 tutor-mb-16 tutor-mb-lg-0">
            <label class="tutor-form-label">
                <?php esc_html_e('Filtrar por categoría', 'tutor'); ?>
            </label>
            <select class="tutor-form-select tutor-form-control" data-search="no">
                <option value=""><?php esc_html_e('Todas las categorías', 'tutor'); ?></option>
                <option value="RC"><?php esc_html_e('R.C. Recursos de consulta', 'tutor'); ?></option>
                <option value="RAD"><?php esc_html_e('R.A.D. Recursos de Apoyo para Docentes', 'tutor'); ?></option>
                <option value="RDC"><?php esc_html_e('R.D.C. Recursos Didácticos Complementarios', 'tutor'); ?></option>
                <option value="RL"><?php esc_html_e('R.L. Recursos Lúdicos', 'tutor'); ?></option>
                <option value="RTE"><?php esc_html_e('R.T.E. Recursos de Trabajo para el Estudiante', 'tutor'); ?></option>
            </select>
        </div>
    </div>

    <?php if($resources && count($resources) > 0): ?>
        <div class="table-outer-wrapper">
        <div class="tutor-table-responsive">
            <table class="tutor-table tutor-table-middle">
                <thead>
                    <tr>
                        <th width="5%"><?php esc_html_e('ID', 'tutor'); ?></th>
                        <th width="15%"><?php esc_html_e('Título', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Subtítulo', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Categoría', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Autor', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Email del Autor', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Procedencia', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('País', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Área de Conocimiento', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Áreas en Otros Países', 'tutor'); ?></th>
                        <th width="15%"><?php esc_html_e('Descripción', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Fecha de Publicación', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Idioma', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Secuencia Escolar', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Nivel en Otros Países', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Tipo de Archivo', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Formato Visual', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Usuario Destinatario', 'tutor'); ?></th>
                        <th width="15%"><?php esc_html_e('Habilidades y Competencias', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Licencia', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Calificación CAB', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Sello CAB', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Acciones', 'tutor'); ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php if($resources && count($resources) > 0): ?>
                        <?php foreach ($resources as $resource): ?>
                            <tr>
                                <td>
                                    <span class="tutor-text-regular-body tutor-color-black">
                                        <?php echo esc_html($resource->id); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="tutor-d-flex tutor-flex-column">
                                        <span class="tutor-fs-6 tutor-fw-medium tutor-color-black">
                                            <?php echo esc_html($resource->title); ?>
                                        </span>
                                    </div>
                                </td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->subtitle); ?></span></td>
                                <td>
                                    <span class="tutor-badge-outline-primary">
                                        <?php echo esc_html($resource->category); ?>
                                    </span>
                                </td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->author); ?></span></td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->author_email); ?></span></td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->origin); ?></span></td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->country); ?></span></td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->knowledge_area); ?></span></td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->knowledge_area_other_countries); ?></span></td>
                                <td>
                                    <div class="tutor-fs-7 description-content">
                                        <?php echo esc_html($resource->description); ?>
                                        <button class="tutor-btn tutor-btn-outline-primary tutor-btn-sm toggle-description">
                                            <?php esc_html_e('Ver más', 'tutor'); ?>
                                        </button>
                                    </div>
                                </td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->publication_date); ?></span></td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->language); ?></span></td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->school_sequence); ?></span></td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->level_other_countries); ?></span></td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->file_type); ?></span></td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->visual_format); ?></span></td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->target_user); ?></span></td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->skills_competencies); ?></span></td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->license); ?></span></td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->cab_rating); ?></span></td>
                                <td><span class="tutor-fs-7"><?php echo esc_html($resource->cab_seal); ?></span></td>
                                <td>
                                    <div class="tutor-d-flex tutor-align-center tutor-gap-1">
                                        <button type="button" class="tutor-btn tutor-btn-outline-primary tutor-btn-sm" data-tutor-modal-target="resource-details-<?php echo esc_attr($resource->id); ?>">
                                            <span class="tutor-icon-eye tutor-mr-8"></span>
                                            <span><?php esc_html_e('Ver detalles', 'tutor'); ?></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="23">
                                <div class="tutor-empty-state td-empty-state">
                                    <div class="tutor-fs-6 tutor-color-secondary tutor-text-center">
                                        <?php esc_html_e('No hay recursos disponibles.', 'tutor'); ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
</div>
        </div>
        
    <?php else: ?>
        <div class="tutor-empty-state td-empty-state">
            <img src="<?php echo esc_url(tutor()->url . 'assets/images/empty-state.svg'); ?>" alt="">
            <div class="tutor-fs-6 tutor-color-secondary tutor-text-center">
                <?php esc_html_e('No hay recursos disponibles.', 'tutor'); ?>
            </div>
        </div>
    <?php endif; ?>
</div>


<script>
jQuery(document).ready(function($) {
    $('.toggle-description').on('click', function() {
        var content = $(this).parent('.description-content');
        content.toggleClass('expanded');
        $(this).text(content.hasClass('expanded') ? 'Ver menos' : 'Ver más');
    });
});
</script>