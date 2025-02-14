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

.editable-field {
    width: 100%;
    padding: 5px;
    border: 1px solid transparent;
    background: transparent;
}

.editable-field:not([readonly]):not([disabled]) {
    border-color: var(--tutor-border-color);
    background: #fff;
}

.edit-btn, .save-btn, .cancel-btn {
    padding: 4px 8px !important;
    margin: 2px !important;
}

.description-content textarea {
    min-height: 100px;
    resize: vertical;
}
/* Estilos para el modo de edición */
.editable-field {
    width: 100%;
    padding: 8px;
    border: 1px solid var(--tutor-border-color);
    border-radius: 4px;
    background-color: #fff;
    font-size: inherit;
    color: inherit;
    transition: all 0.3s ease;
}

/* Estilos para la fila en modo edición */
tr.editing {
    background-color: rgba(62, 100, 222, 0.05);
}

tr.editing td {
    background-color: transparent;
}

/* Estilos para los botones de acción */
.edit-actions {
    display: flex;
    gap: 8px;
}

.edit-actions button {
    padding: 6px 12px;
    font-size: 13px;
}

/* Animaciones */
.editable-field, .display-value {
    transition: all 0.3s ease;
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
                        <th width="8%"><?php esc_html_e('Edad', 'tutor'); ?></th>
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
            <tr data-resource-id="<?php echo esc_attr($resource->id); ?>">
                <td>
                    <span class="tutor-text-regular-body tutor-color-black">
                        <?php echo esc_html($resource->id); ?>
                    </span>
                </td>
                <td>
                    <span class="display-value tutor-fs-6 tutor-fw-medium">
                        <?php echo esc_html($resource->title); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-6 tutor-fw-medium" name="title" 
                           value="<?php echo esc_attr($resource->title); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->subtitle); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-7" name="subtitle" 
                           value="<?php echo esc_attr($resource->subtitle); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-badge-outline-primary">
                        <?php echo esc_html($resource->category); ?>
                    </span>
                    <select class="editable-field tutor-badge-outline-primary" name="category" style="display: none;">
                        <option value="RC" <?php selected($resource->category, 'RC'); ?>>R.C.</option>
                        <option value="RAD" <?php selected($resource->category, 'RAD'); ?>>R.A.D.</option>
                        <option value="RDC" <?php selected($resource->category, 'RDC'); ?>>R.D.C.</option>
                        <option value="RL" <?php selected($resource->category, 'RL'); ?>>R.L.</option>
                        <option value="RTE" <?php selected($resource->category, 'RTE'); ?>>R.T.E.</option>
                    </select>
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->author); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-7" name="author" 
                           value="<?php echo esc_attr($resource->author); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->author_email); ?>
                    </span>
                    <input type="email" class="editable-field tutor-fs-7" name="author_email" 
                           value="<?php echo esc_attr($resource->author_email); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->origin); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-7" name="origin" 
                           value="<?php echo esc_attr($resource->origin); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->country); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-7" name="country" 
                           value="<?php echo esc_attr($resource->country); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->knowledge_area); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-7" name="knowledge_area" 
                           value="<?php echo esc_attr($resource->knowledge_area); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->knowledge_area_other_countries); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-7" name="knowledge_area_other_countries" 
                           value="<?php echo esc_attr($resource->knowledge_area_other_countries); ?>" style="display: none;">
                </td>
                <td>
                    <div class="display-value tutor-fs-7 description-content">
                        <?php echo esc_html($resource->description); ?>
                        <button class="tutor-btn tutor-btn-outline-primary tutor-btn-sm toggle-description">
                            <?php esc_html_e('Ver más', 'tutor'); ?>
                        </button>
                    </div>
                    <textarea class="editable-field tutor-fs-7" name="description" style="display: none;">
                        <?php echo esc_textarea($resource->description); ?>
                    </textarea>
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->publication_date); ?>
                    </span>
                    <input type="date" class="editable-field tutor-fs-7" name="publication_date" 
                           value="<?php echo esc_attr($resource->publication_date); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->language); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-7" name="language" 
                           value="<?php echo esc_attr($resource->language); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->school_sequence); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-7" name="school_sequence" 
                           value="<?php echo esc_attr($resource->school_sequence); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->age); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-7" name="age" 
                           value="<?php echo esc_attr($resource->age); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->level_other_countries); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-7" name="level_other_countries" 
                           value="<?php echo esc_attr($resource->level_other_countries); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->file_type); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-7" name="file_type" 
                           value="<?php echo esc_attr($resource->file_type); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->visual_format); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-7" name="visual_format" 
                           value="<?php echo esc_attr($resource->visual_format); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->target_user); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-7" name="target_user" 
                           value="<?php echo esc_attr($resource->target_user); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->skills_competencies); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-7" name="skills_competencies" 
                           value="<?php echo esc_attr($resource->skills_competencies); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->license); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-7" name="license" 
                           value="<?php echo esc_attr($resource->license); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->cab_rating); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-7" name="cab_rating" 
                           value="<?php echo esc_attr($resource->cab_rating); ?>" style="display: none;">
                </td>
                <td>
                    <span class="display-value tutor-fs-7">
                        <?php echo esc_html($resource->cab_seal); ?>
                    </span>
                    <input type="text" class="editable-field tutor-fs-7" name="cab_seal" 
                           value="<?php echo esc_attr($resource->cab_seal); ?>" style="display: none;">
                </td>
                <td>
                    <div class="tutor-d-flex tutor-align-center tutor-gap-1">
                        <button type="button" class="tutor-btn tutor-btn-outline-primary tutor-btn-sm edit-btn">
                            <span class="tutor-icon-edit"></span>
                            <span>Editar</span>
                        </button>
                        <div class="edit-actions" style="display: none;">
                            <button type="button" class="tutor-btn tutor-btn-primary tutor-btn-sm save-btn">
                                <span>Guardar</span>
                            </button>
                            <button type="button" class="tutor-btn tutor-btn-outline-primary tutor-btn-sm cancel-btn">
                                <span>Cancelar</span>
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="24">
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
    // Manejar el botón de editar
    $('.edit-btn').on('click', function() {
        var row = $(this).closest('tr');
        
        row.find('.display-value').hide();
        row.find('.editable-field').show();
        
        row.find('.edit-btn').hide();
        row.find('.edit-actions').show();
        
        row.addClass('editing');
    });

    // Manejar el botón de cancelar
    $('.cancel-btn').on('click', function() {
        var row = $(this).closest('tr');
        
        // Mostrar valores de visualización y ocultar campos de edición
        row.find('.display-value').show();
        row.find('.editable-field').hide();
        
        // Restaurar valores originales
        row.find('.editable-field').each(function() {
            $(this).val($(this).attr('value'));
        });
        
        // Cambiar botones
        row.find('.edit-btn').show();
        row.find('.edit-actions').hide();
        
        // Remover clase de edición
        row.removeClass('editing');
    });

    // Manejar el botón de guardar
    // Reemplaza el manejador del botón guardar con este código
$('.save-btn').on('click', function() {
    var row = $(this).closest('tr');
    var resourceId = row.data('resource-id');
    var data = {
        action: 'update_resource',
        nonce: ajax_object.nonce,
        resource_id: resourceId,
        resource_data: {}
    };

    // Recolectar datos
    row.find('.editable-field').each(function() {
        var field = $(this);
        var fieldName = field.attr('name');
        data.resource_data[fieldName] = field.val();
    });

    // Mostrar indicador de carga
    var saveBtn = $(this);
    saveBtn.prop('disabled', true).html('<span class="loading-spinner"></span>Guardando...');

    // Enviar AJAX
    $.ajax({
        url: ajax_object.ajax_url,
        type: 'POST',
        data: data,
        success: function(response) {
            if (response.success) {
                // Actualizar valores mostrados
                row.find('.editable-field').each(function() {
                    var field = $(this);
                    var fieldName = field.attr('name');
                    var displayField = row.find('.display-value').filter(function() {
                        return $(this).closest('td').find('.editable-field').attr('name') === fieldName;
                    });
                    
                    if (displayField.length) {
                        displayField.text(field.val());
                    }
                });

                // Restaurar vista
                row.find('.display-value').show();
                row.find('.editable-field').hide();
                row.find('.edit-btn').show();
                row.find('.edit-actions').hide();
                row.removeClass('editing');

                alert('Recurso actualizado exitosamente');
            } else {
                alert('Error al actualizar el recurso');
            }
        },
        error: function(xhr, status, error) {
            alert('Error de conexión: ' + error);
        },
        complete: function() {
            saveBtn.prop('disabled', false).html('<span>Guardar</span>');
        }
    });
});
});
</script>

