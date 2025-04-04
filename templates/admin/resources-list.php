<?php
$current_user = wp_get_current_user();
$user_roles = $current_user->roles;

$resources = array();

try {
    $db = new ERM_Database();

    $is_catalogator = in_array('catalogator', $user_roles);
    $is_evaluator = in_array('evaluator', $user_roles);
    $is_administrator = in_array('administrator', $user_roles);

    if (!$is_catalogator && !$is_evaluator && !$is_administrator) {
        wp_die('No tienes permisos para acceder a esta página.');
    }

    if ($is_administrator) {
        $resources = $db->get_resources_with_min_score(81);
    } elseif ($is_catalogator) {
        $resources = $db->get_resources();
    } elseif ($is_evaluator) {
        $resources = $db->get_approved_resources();
    }

} catch (Exception $e) {
    error_log('Error en resources-list.php: ' . $e->getMessage());
}

$resources = is_array($resources) ? $resources : array();
?>

<link rel="stylesheet" href="<?php echo plugins_url('tutor/assets/css/tutor.min.css'); ?>">

<style>
/* Variables de color y tipografía */
@import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;500;600;700&display=swap');

:root {
    --tutor-primary-color: #0192BD;
    --tutor-primary-hover: #043E54;
    --tutor-primary-light: rgba(1, 146, 189, 0.1);
    --tutor-success-color: #0192BD;
    --tutor-success-light: rgba(1, 146, 189, 0.1);
    --tutor-danger-color: #E23D31;
    --tutor-danger-light: rgba(217, 83, 79, 0.1);
    --tutor-warning-color: #F0AD4E;
    --tutor-warning-light: rgba(240, 173, 78, 0.1);
    --tutor-secondary-color: #2c2f36;
    --tutor-secondary-light: rgba(45, 47, 55, 0.1);
    
    --tutor-text-primary: #2D2F37;
    --tutor-text-secondary: #5B616F;
    --tutor-text-light: #FFFFFF;
    
    --tutor-border-color: #E3E5EB;
    --tutor-border-radius: 6px;
    
    --tutor-bg-light: #F7F9FA;
    --tutor-bg-white: #FFFFFF;
    --tutor-bg-hover: rgba(146, 216, 236, 0.1);
    
    --tutor-shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
    --tutor-shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
    --tutor-shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
    
    --tutor-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

* {
    font-family: 'Nunito Sans', sans-serif;
}

.tutor-wrap {
    padding: 24px;
    background-color: var(--tutor-bg-white);
    border-radius: var(--tutor-border-radius);
    box-shadow: var(--tutor-shadow-sm);
}

.table-outer-wrapper {
    position: relative;
    width: 100%;
    margin-top: 24px;
    border: 1px solid var(--tutor-border-color);
    border-radius: var(--tutor-border-radius);
    background-color: var(--tutor-bg-white);
    overflow: hidden;
    box-shadow: var(--tutor-shadow-sm);
}

.tutor-table-responsive {
    position: relative;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    max-height: calc(100vh - 200px);
}

.tutor-table {
    width: 100%;
    min-width: 1500px;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 14px;
    color: var(--tutor-text-secondary);
    background-color: var(--tutor-bg-white);
}

.tutor-table th {
    position: sticky;
    top: 0;
    z-index: 10;
    padding: 16px 20px;
    font-weight: 600;
    text-align: left;
    background-color: var(--tutor-secondary-color);
    color: var(--tutor-text-light);
    border-bottom: 1px solid var(--tutor-border-color);
    white-space: nowrap;
}

.tutor-table td {
    padding: 12px 20px;
    border-bottom: 1px solid var(--tutor-border-color);
    vertical-align: middle;
    transition: var(--tutor-transition);
}
	
	.tutor-table td {
    min-width: 100px; /* Ajusta según sea necesario */
    position: relative;
}


.tutor-table tr:last-child td {
    border-bottom: none;
}

.tutor-table tr:hover td {
    background-color: var(--tutor-bg-hover);
}

.tutor-table th:last-child,
.tutor-table td:last-child {
    position: sticky;
    right: 0;
    background-color: var(--tutor-bg-white);
    z-index: 5;
}

.tutor-table th:last-child {
    background-color: var(--tutor-secondary-color);
	z-index: 11;
}

.tutor-table-middle td {
    vertical-align: middle;
}

.tutor-table td[width="15%"],
.tutor-table td:nth-child(11) {
    min-width: 200px;
    max-width: 300px;
    white-space: normal;
}

.tutor-badge {
    display: inline-block;
    padding: 4px 10px;
    font-size: 12px;
    font-weight: 500;
    border-radius: 20px;
    line-height: 1.4;
}

.tutor-badge-outline-primary {
    color: var(--tutor-primary-color);
    background-color: var(--tutor-primary-light);
    border: 1px solid var(--tutor-primary-color);
}

.tutor-badge-outline-success {
    color: var(--tutor-success-color);
    background-color: var(--tutor-success-light);
    border: 1px solid var(--tutor-success-color);
}

.tutor-badge-outline-danger {
    color: var(--tutor-danger-color);
    background-color: var(--tutor-danger-light);
    border: 1px solid var(--tutor-danger-color);
}

.tutor-badge-outline-warning {
    color: var(--tutor-warning-color);
    background-color: var(--tutor-warning-light);
    border: 1px solid var(--tutor-warning-color);
}

.tutor-badge-outline-secondary {
    color: var(--tutor-secondary-color);
    background-color: var(--tutor-secondary-light);
    border: 1px solid var(--tutor-secondary-color);
}

.tutor-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    line-height: 1.5;
    border: 1px solid transparent;
    cursor: pointer;
    transition: var(--tutor-transition);
    text-decoration: none;
    white-space: nowrap;
	border-radius: 3px !important;
}

.tutor-btn-sm {
    padding: 6px 12px;
    font-size: 13px;
}

.tutor-btn-primary {
    background-color: var(--tutor-primary-color);
    color: var(--tutor-text-light);
}

.tutor-btn-primary:hover {
    background-color: var(--tutor-primary-hover);
    transform: translateY(-2px);
    box-shadow: var(--tutor-shadow-sm);
}

.tutor-btn-outline-primary {
    background-color: transparent;
    color: var(--tutor-primary-color);
    border-color: var(--tutor-primary-color);
}

.tutor-btn-outline-primary:hover {
    background-color: var(--tutor-primary-light);
    color: var(--tutor-primary-hover);
}

.tutor-btn-success {
    background-color: var(--tutor-success-color);
    color: var(--tutor-text-light);
}

.tutor-btn-success:hover {
    background-color: var(--tutor-primary-hover);
    transform: translateY(-2px);
}

.tutor-btn-danger {
    background-color: var(--tutor-danger-color);
    color: var(--tutor-text-light);
}

.tutor-btn-danger:hover {
    background-color: #f14538;
    transform: translateY(-2px);
}

.tutor-btn-outline-secondary {
    background-color: transparent;
    color: var(--tutor-secondary-color);
    border-color: var(--tutor-secondary-color);
}

.tutor-btn-outline-secondary:hover {
    background-color: var(--tutor-secondary-light);
}

.tutor-btn-disabled {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}

.tutor-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 6px;
}

.dashicons {
    font-size: 16px;
    line-height: 1;
    vertical-align: middle;
}

.editable-field {
    width: 100%;
    min-width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    padding: 8px 12px;
    border: 1px solid var(--tutor-border-color);
    border-radius: var(--tutor-border-radius);
    font-size: inherit;
    color: inherit;
    background-color: var(--tutor-bg-white);
    transition: var(--tutor-transition);
    margin: 0;
}
	
	

.editable-field:focus {
    outline: none;
    border-color: var(--tutor-primary-color);
    box-shadow: 0 0 0 3px rgba(1, 146, 189, 0.2);
}

textarea.editable-field {
    min-height: 100px;
    resize: vertical;
}

tr.editing {
    background-color: var(--tutor-bg-hover) !important;
}

tr.editing td {
    background-color: transparent;
}

.description-content {
    max-height: 50px;
    overflow: hidden;
    position: relative;
    transition: var(--tutor-transition);
}

.description-content.expanded {
    max-height: none;
}

.description-content::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 20px;
    opacity: 1;
    transition: var(--tutor-transition);
}

.description-content.expanded::after {
    opacity: 0;
}

.toggle-description {
    margin-top: 5px;
    font-size: 12px !important;
    padding: 2px 8px !important;
}

.edit-actions-container {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.edit-actions {
    display: flex;
    gap: 6px;
}

.approval-buttons {
    display: flex;
    gap: 6px;
    margin-bottom: 6px;
}

.tutor-table-responsive::-webkit-scrollbar {
    height: 8px;
    width: 8px;
}

.tutor-table-responsive::-webkit-scrollbar-track {
    background: var(--tutor-bg-light);
    border-radius: 4px;
}

.tutor-table-responsive::-webkit-scrollbar-thumb {
    background: var(--tutor-secondary-color);
    border-radius: 4px;
}

.tutor-table-responsive::-webkit-scrollbar-thumb:hover {
    background: var(--tutor-primary-color);
}

.tutor-empty-state {
    padding: 40px 20px;
    text-align: center;
    color: var(--tutor-text-secondary);
}

.tutor-empty-state .tutor-fs-6 {
    font-size: 16px;
}

.tutor-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.5);
    opacity: 0;
    visibility: hidden;
    transition: var(--tutor-transition);
}

.tutor-modal.active {
    opacity: 1;
    visibility: visible;
}

.tutor-modal-window {
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
    background: var(--tutor-bg-white);
    border-radius: var(--tutor-border-radius);
    overflow: hidden;
    box-shadow: var(--tutor-shadow-lg);
    transform: translateY(-20px);
    transition: var(--tutor-transition);
}

.tutor-modal.active .tutor-modal-window {
    transform: translateY(0);
}

.tutor-modal-content {
    padding: 24px;
    overflow-y: auto;
    max-height: calc(90vh - 60px);
}

.tutor-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 24px;
    border-bottom: 1px solid var(--tutor-border-color);
}

.tutor-modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: var(--tutor-text-secondary);
    transition: var(--tutor-transition);
}

.tutor-modal-close:hover {
    color: var(--tutor-danger-color);
}

.evaluation-item {
    margin-bottom: 16px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--tutor-border-color);
}

.evaluation-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.evaluation-item select {
    width: 100%;
    max-width: 200px;
    padding: 8px 12px;
    border: 1px solid var(--tutor-border-color);
    border-radius: var(--tutor-border-radius);
}
	
.tutor-table th:nth-child(1),
.tutor-table td:nth-child(1) {
    width: 1% !important;
    min-width: 1% !important;
}
	
	
	/*ancho editables*/
.editable-field[name="title"] {
    width: 300px !important; 
}

/*
.display-value.tutor-fs-6 {
    display: inline-block;
    width: 300px !important;
} */
	
.tutor-btn.action-btn {
  height: 40px;          
  min-height: 40px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0 16px;        
}

	/*columna acciones nunca oculto*/
	.tutor-table th:last-child,
	.tutor-table td:last-child {
 
  border-left: 1px solid var(--tutor-border-color);
}
	
	.tutor-table tr:hover > td:last-child {
  background-color: var(--tutor-bg-white) !important;
		
}
	.tutor-table tr:hover > td:first-child {
  background-color: var(--tutor-bg-white) !important;
		 position: sticky;
	  left: 0;
	  z-index: 11;
}

	/*columna numeraicon nunca oculto*/
.tutor-table th:first-child {
  position: sticky;
  top: 0;           /* Se fija en la parte superior */
  left: 0;
  z-index: 12;      /* Mayor que el resto si es necesario */
  border-right: 1px solid var(--tutor-border-color);
  /* Puedes conservar o modificar el background según tu estética */
  background-color: var(--tutor-secondary-color);
}

/* Celdas de la primera columna */
.tutor-table td:first-child {
  position: sticky;
  left: 0;
  z-index: 11;
  border-right: 1px solid var(--tutor-border-color);
  background-color: var(--tutor-bg-white);
}
	

/* Botón Rechazado: usa el color rojo actual de tutor-danger-color */
.tutor-btn-danger,
.tutor-btn-outline-danger.tutor-btn-disabled {
  background-color: var(--tutor-danger-color) !important;
  border-color: var(--tutor-danger-color) !important;
  color: var(--tutor-text-light) !important;
}
	
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.spinning {
    animation: spin 1s linear infinite;
}

@media (max-width: 992px) {
    .tutor-table th,
    .tutor-table td {
        padding: 12px 16px;
    }
    
    .tutor-table td[width="15%"],
    .tutor-table td:nth-child(11) {
        min-width: 180px;
        max-width: 250px;
    }
}

@media (max-width: 768px) {
    .table-outer-wrapper {
        margin-left: -20px;
        margin-right: -20px;
        width: calc(100% + 40px);
        border-radius: 0;
        border-left: none;
        border-right: none;
    }
    
    .tutor-table th,
    .tutor-table td {
        padding: 10px 12px;
        font-size: 13px;
    }
    
    .tutor-btn-sm {
        padding: 4px 8px;
        font-size: 12px;
    }
}
	
	

</style>

<style>
/* Estilos para los radio buttons en el modal de evaluación */
.evaluation-item {
  margin-bottom: 16px;
  padding-bottom: 16px;
  border-bottom: 1px solid var(--tutor-border-color);
}

.evaluation-item p {
  margin: 5px 0;
}

.evaluation-options {
  margin-top: 10px;
}

.evaluation-option {
  display: inline-flex;
  align-items: center;
  margin-right: 15px;
  font-size: 14px;
  cursor: pointer;
}

.evaluation-option input[type="radio"] {
  margin-right: 5px;
  /* Opcional: animación o estilos propios para el radio */
}
</style>

<style>
/* Opcional: reglas adicionales para mejorar el modal */
.tutor-modal-content {
  font-family: 'Nunito Sans', sans-serif;
  color: var(--tutor-text-primary);
  padding: 24px;
  background-color: var(--tutor-bg-white);
  border-radius: var(--tutor-border-radius);
}

.tutor-modal-header h3 {
  margin: 0;
}

.tutor-modal-close {
  font-size: 28px;
  color: var(--tutor-text-secondary);
  background: none;
  border: none;
  cursor: pointer;
  transition: color 0.3s ease;
}

.tutor-modal-close:hover {
  color: var(--tutor-danger-color);
}
</style>

<div class="tutor-wrap tutor-dashboard tutor-dashboard-content">
    <div class="tutor-fs-5 tutor-fw-medium tutor-color-black tutor-mb-24">
        <?php 
        if ($is_catalogator) {
            echo esc_html('Panel de Catalogador - Recursos Educativos');
        } elseif ($is_evaluator) {
            echo esc_html('Panel de Evaluador - Recursos Educativos Aprobados');
        } else {
            echo esc_html('Panel de Administrador - Recursos Educativos');
        }
        ?>
    </div>

    <!-- Filter Section 
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
	

	
	-->



    <div class="table-outer-wrapper">
        <div class="tutor-table-responsive">
            <table class="tutor-table tutor-table-middle">
                <thead>
                    <tr>
                        <th width="15%"><?php esc_html_e('N°', 'tutor'); ?></th>
                        <th width="100%"><?php esc_html_e('Título', 'tutor'); ?></th>
                        <th width="100%"><?php esc_html_e('Subtítulo', 'tutor'); ?></th>
                        <th width="75%"><?php esc_html_e('Descripción', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Categoría', 'tutor'); ?></th>
                        <th width="15%"><?php esc_html_e('Habilidades/ Competencias Principales', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Área de Conocimiento', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Área de Conocimiento en otros países', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Idioma del RE', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Nivel escolar', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Denominación de Nivel en otros Países', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Edad', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Usuario al que esta dirigido', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Licencia', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Autor/Autores', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Correo del Autor', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('País', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Procedencia/Lugar de Públicación', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Fecha de Publicación', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Tipo de Archivo', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Formato Visual', 'tutor'); ?></th>
                        <th width="12%"><?php esc_html_e('Carga de RE/ Enlace', 'tutor'); ?></th>
                        <th width="12%"><?php esc_html_e('Imagen/ Portada', 'tutor'); ?></th>
                        <!-- Columnas adicionales que se insertan después de "Imagen/ Portada" -->
                        <th width="8%"><?php esc_html_e('Sello CAB', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Estado', 'tutor'); ?></th>
                        <th width="15%"><?php esc_html_e('Razón de Rechazo', 'tutor'); ?></th>
                        <th width="8%"><?php esc_html_e('Puntuación', 'tutor'); ?></th>
                        <th width="10%"><?php esc_html_e('Fecha de Envío', 'tutor'); ?></th>
                        <!-- Acciones -->
                        <th width="10%"><?php esc_html_e('Acciones', 'tutor'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($resources)): ?>
						<?php $total_resources = count($resources); ?>
                        <?php $i = 1; ?>
                        <?php foreach ($resources as $resource): ?>
                            <tr data-resource-id="<?php echo esc_attr($resource->id); ?>">
                                <td>
									<?php echo $total_resources - $i + 1; ?>
								</td>
                                <td>
                                    <span class="display-value tutor-fs-6 tutor-fw-medium"><?php echo esc_html($resource->title); ?></span>
                                    <input type="text" class="editable-field tutor-fs-6 tutor-fw-medium" name="title" 
                                        value="<?php echo esc_attr($resource->title); ?>" style="display: none;">
                                </td>
                                <!-- Subtítulo -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->subtitle); ?></span>
                                    <input type="text" class="editable-field tutor-fs-7" name="subtitle" 
                                        value="<?php echo esc_attr($resource->subtitle); ?>" style="display: none;">
                                </td>
                                <!-- Descripción -->
                                <td>
                                    <div class="display-value tutor-fs-7 description-content"><?php echo esc_html($resource->description); ?></div>
                                    <textarea class="editable-field tutor-fs-7" name="description" style="display: none;"><?php echo esc_textarea($resource->description); ?></textarea>
                                </td>
                                <!-- Categoría -->
                                <td>
                                    <span class="display-value tutor-badge-outline-primary"><?php echo esc_html($resource->category); ?></span>
                                    <select class="editable-field tutor-badge-outline-primary" name="category" style="display: none;">
                                        <option value=""><?php esc_html_e('Seleccione una categoría', 'tutor'); ?></option>
                                        <option value="RC" <?php selected($resource->category, 'RC'); ?>>R.C. Recursos de consulta</option>
                                        <option value="RAD" <?php selected($resource->category, 'RAD'); ?>>R.A.D. Recursos de Apoyo para Docentes</option>
                                        <option value="RDC" <?php selected($resource->category, 'RDC'); ?>>R.D.C. Recursos Didácticos Complementarios</option>
                                        <option value="RL" <?php selected($resource->category, 'RL'); ?>>R.L. Recursos Lúdicos</option>
                                        <option value="RTE" <?php selected($resource->category, 'RTE'); ?>>R.T.E. Recursos de Trabajo para el Estudiante</option>
                                    </select>
                                </td>
                                <!-- Habilidades/ Competencias Principales -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->skills_competencies); ?></span>
                                    <input type="text" class="editable-field tutor-fs-7" name="skills_competencies" 
                                        value="<?php echo esc_attr($resource->skills_competencies); ?>" style="display: none;">
                                </td>
                                <!-- Área de Conocimiento -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->knowledge_area); ?></span>
                                    <input type="text" class="editable-field tutor-fs-7" name="knowledge_area" 
                                        value="<?php echo esc_attr($resource->knowledge_area); ?>" style="display: none;">
                                </td>
                                <!-- Área de Conocimiento en otros países -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->knowledge_area_other_countries); ?></span>
                                    <input type="text" class="editable-field tutor-fs-7" name="knowledge_area_other_countries" 
                                        value="<?php echo esc_attr($resource->knowledge_area_other_countries); ?>" style="display: none;">
                                </td>
                                <!-- Idioma del RE -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->language); ?></span>
                                    <input type="text" class="editable-field tutor-fs-7" name="language" 
                                        value="<?php echo esc_attr($resource->language); ?>" style="display: none;">
                                </td>
                                <!-- Nivel escolar -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->nivel_escolar); ?></span>
                                    <input type="text" class="editable-field tutor-fs-7" name="nivel_escolar" 
                                        value="<?php echo esc_attr($resource->nivel_escolar); ?>" style="display: none;">
                                </td>
                                <!-- Denominación de Nivel en otros Países -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->level_other_countries); ?></span>
                                    <input type="text" class="editable-field tutor-fs-7" name="level_other_countries" 
                                        value="<?php echo esc_attr($resource->level_other_countries); ?>" style="display: none;">
                                </td>
                                <!-- Edad -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->age); ?></span>
                                    <input type="text" class="editable-field tutor-fs-7" name="age" 
                                        value="<?php echo esc_attr($resource->age); ?>" style="display: none;">
                                </td>
                                <!-- Usuario al que esta dirigido -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->target_user); ?></span>
                                    <input type="text" class="editable-field tutor-fs-7" name="target_user" 
                                        value="<?php echo esc_attr($resource->target_user); ?>" style="display: none;">
                                </td>
                                <!-- Licencia -->
                                <td>
                                    <span class="display-value tutor-badge-outline-primary"><?php echo esc_html($resource->license); ?></span>
                                    <select class="editable-field tutor-badge-outline-primary" name="license" style="display: none;">
                                        <option value="CC BY" <?php selected($resource->license, 'CC BY'); ?>>CC BY - Atribución</option>
                                        <option value="CC BY-SA" <?php selected($resource->license, 'CC BY-SA'); ?>>CC BY-SA - Atribución-CompartirIgual</option>
                                        <option value="CC BY-ND" <?php selected($resource->license, 'CC BY-ND'); ?>>CC BY-ND - Atribución-SinDerivadas</option>
                                        <option value="CC BY-NC" <?php selected($resource->license, 'CC BY-NC'); ?>>CC BY-NC - Atribución-NoComercial</option>
                                        <option value="CC BY-NC-SA" <?php selected($resource->license, 'CC BY-NC-SA'); ?>>CC BY-NC-SA - Atribución-NoComercial-CompartirIgual</option>
                                        <option value="CC BY-NC-ND" <?php selected($resource->license, 'CC BY-NC-ND'); ?>>CC BY-NC-ND - Atribución-NoComercial-SinDerivadas</option>
                                        <option value="GPL" <?php selected($resource->license, 'GPL'); ?>>GPL</option>
                                        <option value="MIT" <?php selected($resource->license, 'MIT'); ?>>MIT</option>
                                        <option value="Apache" <?php selected($resource->license, 'Apache'); ?>>Apache</option>
                                        <option value="Public Domain" <?php selected($resource->license, 'Public Domain'); ?>>Dominio Público</option>
                                    </select>
                                </td>
                                <!-- Autor/Autores -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->author); ?></span>
                                    <input type="text" class="editable-field tutor-fs-7" name="author" 
                                        value="<?php echo esc_attr($resource->author); ?>" style="display: none;">
                                </td>
                                <!-- Correo del Autor -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->author_email); ?></span>
                                    <input type="email" class="editable-field tutor-fs-7" name="author_email" 
                                        value="<?php echo esc_attr($resource->author_email); ?>" style="display: none;">
                                </td>
                                <!-- País -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->country); ?></span>
                                    <input type="text" class="editable-field tutor-fs-7" name="country" 
                                        value="<?php echo esc_attr($resource->country); ?>" style="display: none;">
                                </td>
                                <!-- Procedencia/Lugar de Públicación -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->origin); ?></span>
                                    <input type="text" class="editable-field tutor-fs-7" name="origin" 
                                        value="<?php echo esc_attr($resource->origin); ?>" style="display: none;">
                                </td>
                                <!-- Fecha de Publicación -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->publication_date); ?></span>
                                    <input type="date" class="editable-field tutor-fs-7" name="publication_date" 
                                        value="<?php echo esc_attr($resource->publication_date); ?>" style="display: none;">
                                </td>
                                <!-- Tipo de Archivo -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->file_type); ?></span>
                                    <input type="text" class="editable-field tutor-fs-7" name="file_type" 
                                        value="<?php echo esc_attr($resource->file_type); ?>" style="display: none;">
                                </td>
                                <!-- Formato Visual -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->visual_format); ?></span>
                                    <input type="text" class="editable-field tutor-fs-7" name="visual_format" 
                                        value="<?php echo esc_attr($resource->visual_format); ?>" style="display: none;">
                                </td>
                                <!-- Carga de RE/ Enlace -->
                                <td>
                                    <?php if (!empty($resource->file_url)): ?>
                                        <a href="<?php echo esc_url($resource->file_url); ?>" target="_blank" class="tutor-btn tutor-btn-outline-primary tutor-btn-sm">
                                            <span class="dashicons dashicons-download"></span>
                                            Descargar <?php echo esc_html(strtoupper($resource->file_type)); ?>
                                        </a>
                                    <?php elseif (!empty($resource->file_link)): ?>
                                        <a href="<?php echo esc_url($resource->file_link); ?>" target="_blank" class="tutor-btn tutor-btn-outline-secondary tutor-btn-sm">
                                            <span class="dashicons dashicons-external"></span>
                                            Ver enlace
                                        </a>
                                    <?php else: ?>
                                        <span class="tutor-badge-outline-warning">Sin archivo</span>
                                    <?php endif; ?>
                                </td>
                                <!-- Imagen/ Portada -->
                                <td>
                                    <?php if (!empty($resource->cover_image)): ?>
                                        <img src="<?php echo esc_url($resource->cover_image); ?>" alt="Portada" style="max-width:80px; height:auto;">
                                    <?php else: ?>
                                        <span class="tutor-badge-outline-warning">Sin imagen</span>
                                    <?php endif; ?>
                                </td>
                                <!-- Sello CAB -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->cab_seal); ?></span>
                                    <input type="text" class="editable-field tutor-fs-7" name="cab_seal" 
                                        value="<?php echo esc_attr($resource->cab_seal); ?>" style="display: none;">
                                </td>
                                <!-- Estado -->
                                <td>
                                    <?php if (isset($resource->approved_by_catalogator)): ?>
                                        <span class="tutor-badge-outline-<?php echo $resource->approved_by_catalogator ? 'success' : 'danger'; ?>">
                                            <?php echo $resource->approved_by_catalogator ? 'Aprobado' : 'Rechazado'; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="tutor-badge-outline-warning">Pendiente</span>
                                    <?php endif; ?>
                                </td>
                                <!-- Razón de Rechazo -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->rejection_reason); ?></span>
                                    <input type="text" class="editable-field tutor-fs-7" name="rejection_reason" 
                                        value="<?php echo esc_attr($resource->rejection_reason); ?>" style="display: none;">
                                </td>
                                <!-- Puntuación -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo $resource->evaluation_score ? number_format($resource->evaluation_score, 2) : '-'; ?></span>
                                </td>
                                <!-- Fecha de Envío -->
                                <td>
                                    <span class="display-value tutor-fs-7"><?php echo esc_html($resource->submission_date); ?></span>
                                </td>
                                <!-- Acciones -->
                                <td>
                                    <?php if ($is_administrator && !empty($resource->file_url)): ?>
                                        <button type="button" class="tutor-btn tutor-btn-primary tutor-btn-sm upload-to-dspace-btn" 
                                            data-resource-id="<?php echo esc_attr($resource->id); ?>"
                                            data-title="<?php echo esc_attr($resource->title); ?>"
                                            data-file-url="<?php echo esc_attr($resource->file_url); ?>">
                                            <span class="dashicons dashicons-upload"></span>
                                            Subir a DSpace
                                        </button>
                                    <?php endif; ?>
                                    <div class="tutor-d-flex tutor-align-center tutor-gap-1">
                                        <?php if ($is_catalogator): ?>
                                            <?php if (!isset($resource->approved_by_catalogator) || $resource->approved_by_catalogator === null): ?>
                                                <div class="approval-buttons">
                                                    <button type="button" class="tutor-btn tutor-btn-success tutor-btn-sm approve-btn" 
                                                        data-resource-id="<?php echo esc_attr($resource->id); ?>"
                                                        data-author-email="<?php echo esc_attr($resource->author_email); ?>">
                                                        Aprobar
                                                    </button>
                                                    <button type="button" class="tutor-btn tutor-btn-danger tutor-btn-sm reject-btn" 
                                                        data-resource-id="<?php echo esc_attr($resource->id); ?>"
                                                        data-author-email="<?php echo esc_attr($resource->author_email); ?>">
                                                        Rechazar
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                            <div class="edit-actions-container">
                                                <?php if ($is_catalogator): ?>
                                                    <?php 
                                                    $is_approved_or_rejected = isset($resource->approved_by_catalogator) && $resource->approved_by_catalogator !== null;
                                                    $status_text = '';
                                                    $status_class = '';
                                                    if ($is_approved_or_rejected) {
                                                        if ($resource->approved_by_catalogator) {
                                                            $status_text = 'Aprobado';
                                                            $status_class = 'success';
                                                        } else {
                                                            $status_text = 'Rechazado';
                                                            $status_class = 'danger';
                                                        }
                                                    }
                                                    ?>
                                                    <?php if ($is_approved_or_rejected): ?>
                                                        <?php if ($resource->approved_by_catalogator): // Aprobado ?>
    <button type="button" class="tutor-btn tutor-btn-success tutor-btn-sm tutor-btn-disabled" disabled title="No se puede editar un recurso ya aprobado">
        Aprobado
    </button>
<?php else: // Rechazado ?>
    <button type="button" class="tutor-btn tutor-btn-danger tutor-btn-sm tutor-btn-disabled" disabled title="No se puede editar un recurso ya rechazado">
        Rechazado
    </button>
<?php endif; ?>
                                                    <?php else: ?>
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
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php elseif ($is_evaluator): ?>
                                            <button type="button" class="tutor-btn tutor-btn-primary tutor-btn-sm evaluate-btn" data-resource-id="<?php echo esc_attr($resource->id); ?>" data-category="<?php echo esc_attr($resource->category); ?>">
                                                Evaluar
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
						<?php $i++; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="23">
                                <div class="tutor-empty-state td-empty-state" style="padding: 30px; text-align: center;">
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
</div>

<div id="evaluation-modal" class="tutor-modal" style="display: none;">
    <div class="tutor-modal-overlay"></div>
    <div class="tutor-modal-window">
        <div class="tutor-modal-content">
            <div class="tutor-modal-header">
                <h3 class="tutor-fs-5 tutor-fw-medium tutor-color-black">
                    Evaluación del Recurso Educativo
                </h3>
                <button class="tutor-modal-close">×</button>
            </div>

            <div class="tutor-modal-body">
                <form id="evaluation-form">
                    <?php wp_nonce_field('submit_evaluation', 'evaluation_nonce'); ?>
                    <input type="hidden" id="resource-id-eval" name="resource_id">
                    <input type="hidden" id="resource-category" name="resource_category">

                    <?php
                    $criteria_matrix = array(
                        "Evidencia un propósito educativo e intencionalidad o finalidad pedagógica, tomando en cuenta el nivel y el área en el que se presentan." => ['NA','I','I','V','V'],
                        "El objetivo del RE es fácil de identificar, está formulado de manera clara y comprensible para el usuario." => ['NA', 'I', 'I', 'V', 'NA'],
                        "Respeta las leyes de derechos de autor (uso de imágenes, videos, licencia abierta o de creative commons) y se cita o reconoce correctamente." => ['I', 'I', 'I', 'I', 'I'],
                        'El RE se puede contextualizar, adecuar, adaptar, reutilizar, integrar y/o compartir fácilmente con otros materiales.' => ['V', 'V', 'V', 'V', 'V'],
                        'El RE cumple con las leyes de privacidad y protección de datos aplicables.' => ['I', 'I', 'I', 'I', 'I'],
                        'Presenta sus referencias (fuentes de información) de acuerdo con las normas de citación y referencias bibliográficas (Normas APA, Chicago...), y/o proviene de fuentes confiables.' => ['I', 'I', 'I', 'V', 'V'],
                        'El contenido del RE está actualizado con base en fuentes confiables que muestran avances en la disciplina que aborda.' => ['I', 'V', 'I', 'NA', 'I'],
                        'El contenido del RE presenta afirmaciones, explicaciones, ideas, procedimientos, entre otros, en coherencia con las fuentes citadas.' => ['I', 'V', 'V', 'NA', 'NA'],
                        'El RE promueve la adaptación para usuarios con necesidades especiales.' => ['NA', 'V', 'V', 'NA', 'NA'],
                        'El RE permite al usuario elegir la forma en que interactúa con la diversidad de contenidos según su nivel de aprendizaje.' => ['V', 'NA', 'V', 'V', 'V'],
                        'Proporciona contenido abierto y accesible para una comunidad amplia de usuarios.' => ['V', 'V', 'V', 'V', 'V'],
                        'El RE es responsivo y permite visualización en diversas plataformas y dispositivos.' => ['I', 'I', 'I', 'I', 'I'],
                        'El RE promueve el aprendizaje significativo del estudiante (relaciona conceptos nuevos con los que ya conoce).' => ['V', 'NA', 'V', 'V', 'V'],
                        'Plantea herramientas que brindan a los usuarios la posibilidad de retomar sus conocimientos/saberes previos y experiencias para el desarrollo de sus aprendizajes.' => ['V', 'NA', 'V', 'V', 'V'],
                        'Plantea tareas/actividades o preguntas que activan los conocimientos/saberes previos relacionados con el/los propósito(s) de aprendizaje.' => ['V', 'NA', 'V', 'V', 'V'],
                        'Proporciona, a los usuarios, oportunidades de conocimientos/procedimientos/ideas aprendidas en situaciones prácticas y reales.' => ['V', 'V', 'V', 'V', 'V'],
                        'El RE promueve actitudes positivas y/o de reconocimiento por los logros de los usuarios.' => ['NA', 'NA', 'V', 'V', 'V'],
                        'El RE está orientado a mantener la atención y el interés del usuario.' => ['V', 'NA', 'V', 'I', 'V'],
                        'Cuenta con herramientas que promueven la gestión individual y compartida de los aprendizajes en pro de la resolución de problemas.' => ['NA', 'NA', 'V', 'NA', 'V'],
                        'Fomenta la creatividad e innovación para que el estudiante genere nuevas ideas y formas de aplicación.' => ['V', 'NA', 'V', 'V', 'V'],
                        'Orienta una secuencia de tareas/actividades que promueven el pensamiento crítico y/o creativo.' => ['V', 'NA', 'V', 'NA', 'V'],
                        'Proporciona al usuario el desarrollo de habilidades para tomar decisiones informadas y éticas.' => ['V', 'V', 'V', 'V', 'V'],
                        'Dispone de guías de uso, videos tutoriales y demás materiales que ayuden a los usuarios en el manejo del recurso.' => ['V', 'NA', 'V', 'I', 'V'],
                        'El RE está libre de publicidad y propaganda que evidencien conflicto de intereses.' => ['I', 'I', 'I', 'I', 'I']
                    );
                    ?>

<div id="criteria-container" class="evaluation-sections">
                        <?php foreach ($criteria_matrix as $criterion => $values): ?>
                            <div class="evaluation-section tutor-mb-32">
                                <h4 class="tutor-fs-6 tutor-fw-medium tutor-color-black tutor-mb-16">
                                    <?php echo esc_html($criterion); ?>
                                </h4>
                                <div class="evaluation-options">
                                    <label class="evaluation-checkbox">
                                        <input type="radio" name="criterion_<?php echo sanitize_key($criterion); ?>" value="0.25">
                                        <span class="checkmark"></span>
                                        <span class="label-text">Básico (0.25)</span>
                                    </label>
                                    <label class="evaluation-checkbox">
                                        <input type="radio" name="criterion_<?php echo sanitize_key($criterion); ?>" value="0.5">
                                        <span class="checkmark"></span>
                                        <span class="label-text">Intermedio (0.5)</span>
                                    </label>
                                    <label class="evaluation-checkbox">
                                        <input type="radio" name="criterion_<?php echo sanitize_key($criterion); ?>" value="1">
                                        <span class="checkmark"></span>
                                        <span class="label-text">Avanzado (1.0)</span>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="tutor-d-flex tutor-justify-end tutor-mt-32">
                        <button type="submit" class="tutor-btn tutor-btn-primary">
                            Enviar Evaluación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function loadCriteria(category) {
  const criteriaContainer = document.getElementById('criteria-container');
  const criteriaMatrixPHP = <?php echo json_encode($criteria_matrix); ?>;
  
  const categoryIndex = {
    'RC': 0,
    'RAD': 1,
    'RDC': 2,
    'RL': 3,
    'RTE': 4
  };
  
  criteriaContainer.innerHTML = '';
  
  let index = 1;
  for (const [criterion, values] of Object.entries(criteriaMatrixPHP)) {
    const type = values[categoryIndex[category]];
    
    if (type !== 'NA') {
      const criterionHtml = `
        <div class="evaluation-item">
          <p><strong>${index}. ${criterion}</strong></p>
          <p class="criterion-type">(${type === 'I' ? 'Indispensable' : 'Valorado'})</p>
          <div class="evaluation-options">
            <label class="evaluation-option">
              <input type="radio" name="criterion_${index - 1}" value="0.25" required>
              <span>Básico (0.25)</span>
            </label>
            <label class="evaluation-option">
              <input type="radio" name="criterion_${index - 1}" value="0.50" required>
              <span>Intermedio (0.50)</span>
            </label>
            <label class="evaluation-option">
              <input type="radio" name="criterion_${index - 1}" value="1.00" required>
              <span>Avanzado (1.00)</span>
            </label>
          </div>
        </div>
      `;
      criteriaContainer.innerHTML += criterionHtml;
      index++;
    }
  }
  
  if (criteriaContainer.innerHTML === '') {
    criteriaContainer.innerHTML = '<p>No hay criterios definidos para esta categoría.</p>';
  }
}

function openEvaluationModal(resourceId, category) {
    document.getElementById('resource-id-eval').value = resourceId;
    document.getElementById('resource-category').value = category;
	document.getElementById('evaluation-modal').classList.add('active');
    loadCriteria(category);
}
</script>

<script>
jQuery(document).ready(function($) {

    $('.upload-to-dspace-btn').on('click', function() {
        var resourceId = $(this).data('resource-id');
        var title = $(this).data('title');
        var fileUrl = $(this).data('file-url');
        
        $(this).html('<span class="dashicons dashicons-update spinning"></span> Subiendo...');
        var $button = $(this);
        
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'upload_to_dspace',
                nonce: ajax_object.nonce,
                resource_id: resourceId,
                title: title,
                file_url: fileUrl
            },
            success: function(response) {
                if (response.success) {
                    $button.html('<span class="dashicons dashicons-yes"></span> Subido');
                    $button.removeClass('tutor-btn-primary').addClass('tutor-btn-success');
                    alert('Recurso subido a DSpace correctamente.');
                } else {
                    $button.html('<span class="dashicons dashicons-no"></span> Error');
                    $button.removeClass('tutor-btn-primary').addClass('tutor-btn-danger');
                    alert('Error al subir a DSpace: ' + response.data);
                }
            },
            error: function() {
                $button.html('<span class="dashicons dashicons-no"></span> Error');
                $button.removeClass('tutor-btn-primary').addClass('tutor-btn-danger');
                alert('Error en la conexión. Intente nuevamente.');
            }
        });
    });

    $('.evaluate-btn').on('click', function() {
        const resourceId = $(this).data('resource-id');
        const category = $(this).data('category');
		openEvaluationModal(resourceId, category);
        $('#resource-id-eval').val(resourceId);
        $('#resource-category').val(category);
        $('#evaluation-modal').show();
    });


    $('.tutor-modal-close, .tutor-modal-overlay').on('click', function() {
    $('#evaluation-modal').removeClass('active');
});

$(window).on('click', function(event) {
    if ($(event.target).hasClass('tutor-modal-overlay')) {
        $('#evaluation-modal').removeClass('active');
    }
});

$('#evaluation-form').on('submit', function(e) {
    e.preventDefault();
    
    var evaluationData = {};
    $(this).find('input[type="radio"]:checked').each(function() {
        evaluationData[$(this).attr('name')] = $(this).val();
    });
    
    $.ajax({
        url: ajax_object.ajax_url,
        type: 'POST',
        data: {
            action: 'save_evaluation',
            nonce: ajax_object.nonce,
            resource_id: $('#resource-id-eval').val(),
            evaluation_data: evaluationData
        },
        success: function(response) {
            if (response.success) {
                alert(response.data.message);
                $('#evaluation-modal').hide();
                location.reload();
            } else {
                alert(response.data || 'Error al guardar la evaluación');
            }
        },
        error: function(xhr, status, error) {
            alert('Error de conexión: ' + error);
        }
    });
});

$('.edit-btn').on('click', function() {
    var row = $(this).closest('tr');
    
    // Capturar el contenido de la descripción antes de ocultarlo
    var descriptionText = row.find('.description-content').text().trim();
    row.find('textarea[name="description"]').val(descriptionText);
    
    // Ocultar los elementos display y mostrar los campos editables
    row.find('.display-value').hide();
    row.find('.editable-field').show();
    
    // Ocultar el botón editar y mostrar los botones de guardar y cancelar
    row.find('.edit-btn').hide();
    row.find('.edit-actions').show();
    
    // <-- Agrega la siguiente línea para ocultar los botones de aprobación y rechazo
    row.find('.approval-buttons').hide();
    
    row.addClass('editing');
    
    var categoryValue = row.find('td:nth-child(5) .display-value').text().trim();
    row.find('select[name="category"]').val(categoryValue);

    var licenseSpan = row.find('td:nth-child(14) .display-value'); // Cambiado a columna 13
    var licenseValue = licenseSpan.text().trim();
    row.find('select[name="license"]').val(licenseValue);
});

    $('.cancel-btn').on('click', function() {
        var row = $(this).closest('tr');
        
        row.find('.display-value').show();
        row.find('.editable-field').hide();
        
        row.find('.editable-field').each(function() {
            $(this).val($(this).attr('value'));
        });
        
        row.find('.edit-btn').show();
        row.find('.edit-actions').hide();
        row.find('.approval-buttons').show();
        row.removeClass('editing');
    });

    $('.approve-btn').on('click', function() {
    var resourceId = $(this).data('resource-id');
    var authorEmail = $(this).data('author-email');
    
    $.ajax({
        url: ajax_object.ajax_url,
        type: 'POST',
        data: {
            action: 'approve_resource',
            nonce: ajax_object.nonce,
            resource_id: resourceId,
            author_email: authorEmail
        },
        success: function(response) {
            if (response.success) {
                var row = $('[data-resource-id="' + resourceId + '"]');
                var approvalButtons = row.find('.approval-buttons');
                // approvalButtons.replaceWith(
                //     '<span class="tutor-badge-outline-success">Aprobado</span>'
                // );
                
                var editActionsContainer = row.find('.edit-actions-container');
                editActionsContainer.html(
                    '<button type="button" class="tutor-btn tutor-btn-outline-success tutor-btn-sm tutor-btn-disabled" disabled ' +
                    'title="No se puede editar un recurso ya aprobado">' +
                    'Aprobado' +
                    '</button>'
                );
                
                // row.find('td:nth-child(24)').html(
                //     '<span class="tutor-badge-outline-success">Aprobado</span>'
                // );
                
                alert('Recurso aprobado exitosamente');
                
                // Opcional: recargar la página para asegurar que todo se actualice correctamente
                location.reload();
            } else {
                alert('Error al aprobar el recurso');
            }
        },
        error: function(xhr, status, error) {
            alert('Error de conexión: ' + error);
        }
    });
});

$('.reject-btn').on('click', function() {
    var resourceId = $(this).data('resource-id');
    var authorEmail = $(this).data('author-email');
    
    var reason = prompt('Por favor, ingrese el motivo del rechazo:');
    if (reason === null) return; 
    
    $.ajax({
        url: ajax_object.ajax_url,
        type: 'POST', 
        data: {
            action: 'reject_resource',
            nonce: ajax_object.nonce, 
            resource_id: resourceId,
            author_email: authorEmail,
            rejection_reason: reason
        },
        success: function(response) {
            if (response.success) {
                // Actualizar la interfaz - botones de aprobación
                var row = $('[data-resource-id="' + resourceId + '"]');
                var approvalButtons = row.find('.approval-buttons');
                // approvalButtons.replaceWith(
                //     '<span class="tutor-badge-outline-danger">Rechazado</span>' +
                //     '<i class="tutor-icon-info" title="' + reason + '"></i>'
                // );
                
                // Actualizar la interfaz - botones de edición
                var editActionsContainer = row.find('.edit-actions-container');
                editActionsContainer.html(
                    '<button type="button" class="tutor-btn tutor-btn-outline-danger tutor-btn-sm tutor-btn-disabled" disabled ' +
                    'title="No se puede editar un recurso ya rechazado">' +
                    'Rechazado' +
                    '</button>'
                );
                
                // // Actualizar la columna de estado
                // row.find('td:nth-child(24)').html(
                //     '<span class="tutor-badge-outline-danger">Rechazado</span>'
                // );
                
                // Actualizar la columna de razón de rechazo
                row.find('td:nth-child(25) .display-value').text(reason);
                
                alert('Recurso rechazado exitosamente');
                
                // Opcional: recargar la página para asegurar que todo se actualice correctamente
                location.reload();
            } else {
                alert('Error al rechazar el recurso');
            }
        },
        error: function(xhr, status, error) {
            alert('Error de conexión: ' + error);
        }
    });
});

    // Manejar el botón de guardar
  // Manejar el botón de guardar
$('.save-btn').on('click', function() {
    var row = $(this).closest('tr');
    var resourceId = row.data('resource-id');
    
    // Crear objeto para almacenar los datos
    var resourceData = {};
    
    // Recolectar datos de todos los campos editables
     row.find('.editable-field').each(function() {
        var fieldName = $(this).attr('name');
        var fieldValue = $(this).val();
        
        // Asegurarse de que el nombre del campo existe
        if (fieldName) {
            // Recortar espacios en blanco para todos los campos de texto
            if (typeof fieldValue === 'string') {
                fieldValue = fieldValue.trim();
            }
            resourceData[fieldName] = fieldValue;
        }
    });
    
    // Mostrar indicador de carga
    var saveBtn = $(this);
    saveBtn.prop('disabled', true).html('<span class="dashicons dashicons-update spinning"></span> Guardando...');
    
    // Enviar solicitud AJAX
    $.ajax({
        url: ajax_object.ajax_url,
        type: 'POST',
        data: {
            action: 'update_resource',
            nonce: ajax_object.nonce,
            resource_id: resourceId,
            resource_data: resourceData
        },
        success: function(response) {
            if (response.success) {
                // Actualizar los valores mostrados en la interfaz
                for (var fieldName in resourceData) {
                    var value = resourceData[fieldName];
                    var displayElement = row.find('td').filter(function() {
                        return $(this).find('.editable-field[name="' + fieldName + '"]').length > 0;
                    }).find('.display-value');
                    
                    // Actualizar el texto mostrado
                    if (displayElement.length) {
                        displayElement.text(value);
                    }
                    if (typeof resourceData[fieldName] === 'string') {
                        resourceData[fieldName] = resourceData[fieldName].trim();
                    }
                }
                
                // Restaurar la vista normal
                row.find('.display-value').show();
                row.find('.editable-field').hide();
                row.find('.edit-btn').show();
                row.find('.edit-actions').hide();
				row.find('.approval-buttons').show();
                row.removeClass('editing');
                
                alert('Recurso actualizado exitosamente');
                location.reload();
            } else {
                alert('Error al actualizar el recurso: ' + (response.data || 'Error desconocido'));
            }
        },
        error: function(xhr, status, error) {
            console.error('Error AJAX:', xhr.responseText);
            alert('Error de conexión: ' + error);
        },
        complete: function() {
            // Restaurar el botón
            saveBtn.prop('disabled', false).html('<span>Guardar</span>');
        }
    });
});
});


document.head.insertAdjacentHTML('beforeend', `
    <style>
        .spinning {
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .tutor-btn-outline-secondary {
            color: #6c757d;
            border-color: #6c757d;
            margin-left: 5px;
        }

        .tutor-btn-outline-secondary:hover {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }

    </style>
`);
</script>
