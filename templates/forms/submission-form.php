<style>
.error-message {
    color: red;
    font-size: 0.9em;
    margin-top: 5px;
    display: block;
    font-weight: 600;
}
</style>
<form id="educational-resource-form" class="educational-resource-form" novalidate>
    <?php wp_nonce_field('submit_resource_form', 'nonce'); ?>
    <h2>Propuesta de Recurso Educativo</h2>
    <div class="form-columns">
        <div class="form-column">
            <!-- 1. Título del recurso (OBLIGATORIO) -->
            <div class="form-group">
                <label for="title" class="required">
                    1. Título del recurso 
                    <span class="info-icon">
                        ?<span class="tooltip"></span>
                    </span>
                </label>
                <input type="text" id="title" name="title" required>
            </div>

            <!-- 2. Subtítulo (OPCIONAL) -->
            <div class="form-group">
                <label for="subtitle">2. Subtítulo</label>
                <input type="text" id="subtitle" name="subtitle">
            </div>

            <!-- 3. Descripción (OBLIGATORIO) -->
            <div class="form-group">
                <label for="description">3. Descripción</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <!-- 4. Categoría (OBLIGATORIO) -->
            <div class="form-group">
                <label for="category">4. Categoría</label>
                <select id="category" name="category" required>
                    <option value="">Seleccionar Categoría</option>
                    <option value="RC">R.C. Recursos de consulta</option>
                    <option value="RAD">R.A.D. Recursos de Apoyo para Docentes</option>
                    <option value="RDC">R.D.C. Recursos Didácticos Complementarios</option>
                    <option value="RL">R.L. Recursos Lúdicos</option>
                    <option value="RTE">R.T.E. Recursos de Trabajo para el Estudiante</option>
                </select>
            </div>

            <!-- 5. Habilidades/Competencias (OBLIGATORIO) -->
            <div class="form-group">
                <label for="skills_competencies">5. Habilidades/Competencias principales que atiende</label>
                <div class="dropdown-checkbox">
                    <div class="dropdown-header">
                        Seleccionar habilidades y competencias
                        <span class="dropdown-arrow">▼</span>
                    </div>
                    <div class="dropdown-content" id="skillsDropdown">
                        <div class="competency-group">
                            <h4>Socioemocional</h4>
                            <div class="subgroup">
                                <h5>INTRAPERSONAL</h5>
                                <label><input type="checkbox" name="skills_competencies[]" value="autonomia"> Autonomía</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="autoconocimiento"> Autoconocimiento</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="pensamiento_critico"> Pensamiento crítico</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="toma_decisiones"> Toma de decisiones</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="responsabilidad"> Responsabilidad</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="flexibilidad"> Flexibilidad</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="creatividad"> Creatividad</label>
                            </div>
                            <div class="subgroup">
                                <h5>INTERPERSONAL</h5>
                                <label><input type="checkbox" name="skills_competencies[]" value="comunicacion_asertiva"> Comunicación asertiva</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="resolucion_conflictos"> Resolución de conflictos</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="relaciones_colaboracion"> Relaciones de colaboración</label>
                            </div>
                            <div class="subgroup">
                                <h5>CIUDADANÍA</h5>
                                <label><input type="checkbox" name="skills_competencies[]" value="conciencia_social_ambiental"> Conciencia social y ambiental</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="respeto_diversidad"> Respeto a la diversidad y solidaridad</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="participacion_activa"> Participación activa</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="convivencia"> Convivencia</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="democracia"> Democracia</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="sustentabilidad"> Sustentabilidad</label>
                            </div>
                        </div>
                        <div class="competency-group">
                            <h4>Comunicativo - Lingüística</h4>
                            <div class="subgroup">
                                <h5>COMPRESIÓN DE TEXTOS</h5>
                                <label><input type="checkbox" name="skills_competencies[]" value="conocimientos"> Conocimientos</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="comprension_textos"> Comprensión de textos orales y escritos</label>
                            </div>
                            <div class="subgroup">
                                <h5>PRODUCCIÓN DE TEXTOS</h5>
                                <label><input type="checkbox" name="skills_competencies[]" value="produccion_textos"> Producción de textos orales y escritos</label>
                            </div>
                        </div>
                        <div class="competency-group">
                            <h4>Lógico - Matemática</h4>
                            <div class="subgroup">
                                <h5>RESOLUCIÓN DE PROBLEMAS</h5>
                                <label><input type="checkbox" name="skills_competencies[]" value="resuelve_problemas"> Resuelve problemas numéricos</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="formula_problemas"> Formula problemas</label>
                            </div>
                            <div class="subgroup">
                                <h5>ARGUMENTACIÓN</h5>
                                <label><input type="checkbox" name="skills_competencies[]" value="razonamiento_deductivo"> Razonamiento deductivo</label>
                            </div>
                        </div>
                        <div class="competency-group">
                            <h4>Investigación Científica</h4>
                            <div class="subgroup">
                                <h5>CONOCIMIENTO CIENTÍFICO</h5>
                                <label><input type="checkbox" name="skills_competencies[]" value="explicar"> Explicar</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="exponer"> Exponer</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="identificar"> Identificar</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="analizar"> Analizar</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="relacionar"> Relacionar</label>
                            </div>
                            <div class="subgroup">
                                <h5>APLICAR EL CONOCIMIENTO CIENTÍFICO</h5>
                                <label><input type="checkbox" name="skills_competencies[]" value="formular_hipotesis"> Formular hipótesis</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="disenar"> Diseñar</label>
                                <label><input type="checkbox" name="skills_competencies[]" value="experimentar"> Experimentar</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 6. Área de Conocimiento (OBLIGATORIO) -->
            <div class="form-group">
                <label for="knowledge_area">6. Área de Conocimiento</label>
                <input type="text" id="knowledge_area" name="knowledge_area" required>
            </div>

            <!-- 7. Área de conocimiento en otros países (OPCIONAL) -->
            <div class="form-group">
                <label for="knowledge_area_other_countries">
                    7. Área de conocimiento en otros países 
                    <span class="info-icon"><span class="tooltip"></span></span>
                </label>
                <div class="tags-input-container">
                    <div class="input-button-group">
                        <input type="text" id="knowledge_area_other_countries_input" placeholder="Escribe el área de conocimiento">
                        <button type="button" id="add-tag-btn" class="add-tag-button">Agregar</button>
                    </div>
                    <div id="tags-container"></div>
                    <input type="hidden" id="knowledge_area_other_countries" name="knowledge_area_other_countries">
                </div>
            </div>

            <!-- 8. Idioma del RE (OBLIGATORIO) -->
            <div class="form-group">
                <label for="language">
                    8. Idioma del RE 
                    <span class="info-icon"><span class="tooltip"></span></span>
                </label>
                <div class="tags-input-container">
                    <div class="input-button-group">
                        <input type="text" id="language_input" placeholder="Ingrese un idioma">
                        <button type="button" id="add-language-btn" class="add-tag-button">Agregar</button>
                    </div>
                    <div id="language-tags-container"></div>
                    <input type="hidden" id="language" name="language" required>
                </div>
            </div>

            <!-- 9. Nivel escolar (OBLIGATORIO) -->
            <div class="form-group">
                <label for="nivel_escolar">9. Nivel escolar</label>
                <input type="text" id="nivel_escolar" name="nivel_escolar" required>
            </div>

            <!-- 10. Denominación de nivel en otros países (OPCIONAL) -->
            <div class="form-group">
                <label for="level_other_countries">10. Denominación de nivel en otros países</label>
                <input type="text" id="level_other_countries" name="level_other_countries">
            </div>

            <!-- 11. Edad (OBLIGATORIO) -->
            <div class="form-group">
                <label for="age">11. Edad</label>
                <input type="number" id="age" name="age" min="0" max="100" placeholder="Ingrese edad" required>
            </div>

            <!-- 12. Usuario al que está dirigido (OBLIGATORIO) -->
            <div class="form-group">
                <label for="target_user">12. Usuario al que está dirigido</label>
                <input type="text" id="target_user" name="target_user" required>
            </div>
        </div>
        <div class="form-column">
            <!-- 13. Licencia (OBLIGATORIO) -->
            <div class="form-group">
                <label for="license">13. Licencia</label>
                <select id="license" name="license" required>
                    <option value="">Seleccionar licencia</option>
                    <option value="Copyright">Copyright</option>
                    <option value="CC BY">CC BY - Atribución</option>
                    <option value="CC BY-SA">CC BY-SA - Atribución-CompartirIgual</option>
                    <option value="CC BY-ND">CC BY-ND - Atribución-SinDerivadas</option>
                    <option value="CC BY-NC">CC BY-NC - Atribución-NoComercial</option>
                    <option value="CC BY-NC-SA">CC BY-NC-SA - Atribución-NoComercial-CompartirIgual</option>
                    <option value="CC BY-NC-ND">CC BY-NC-ND - Atribución-NoComercial-SinDerivadas</option>
                    <option value="Academic Free License">Academic Free License</option>
                    <option value="GPL">GPL - Licencia Pública General de GNU</option>
                    <option value="MIT">MIT License</option>
                    <option value="Apache">Apache License 2.0</option>
                    <option value="Public Domain">Dominio Público</option>
                    <option value="DISTRIBUCION GRATUITA">Distribución Gratuita</option>
                    <option value="Prohibida su venta">Prohibida su venta</option>
                </select>
            </div>

            <!-- 14. Autor/Autores (OBLIGATORIO) -->
            <div class="form-group">
                <label for="author">
                    14. Autor/Autores 
                    <span class="info-icon"><span class="tooltip"></span></span>
                </label>
                <input type="text" id="author" name="author" required>
            </div>

            <!-- 15. Correo del Autor (OBLIGATORIO) -->
            <div class="form-group">
                <label for="author_email">15. Correo del Autor</label>
                <?php
                $current_user = wp_get_current_user();
                $author_email = '';
                if ( is_user_logged_in() ) {
                    $first_name = $current_user->first_name;
                    $last_name  = $current_user->last_name;
                    $author_name = trim($first_name . ' ' . $last_name);
                    if ( empty( $author_name ) ) {
                        $author_name = $current_user->user_login;
                    }
                    $author_email = $current_user->user_email;
                }
                ?>
                <input type="email" id="author_email" name="author_email" value="<?php echo esc_attr($author_email); ?>" required>
            </div>

            <!-- 16. País (OBLIGATORIO) -->
            <div class="form-group">
                <label for="country">16. País</label>
                <input type="text" id="country" name="country" required>
            </div>

            <!-- 17. Procedencia / Lugar de Públicación (OPCIONAL) -->
            <div class="form-group">
                <label for="origin">17. Procedencia / Lugar de Públicación</label>
                <input type="text" id="origin" name="origin">
            </div>

            <!-- 18. Fecha de Publicación (OBLIGATORIO) -->
            <div class="form-group">
                <label for="publication_date">18. Fecha de Publicación</label>
                <input type="date" id="publication_date" name="publication_date" required>
            </div>

            <!-- 19. Tipo de Archivo (OBLIGATORIO) -->
            <div class="form-group">
                <label for="file_type">19. Tipo de Archivo</label>
                <input type="text" id="file_type" name="file_type" required>
            </div>

            <!-- 20. Formato Visual (OPCIONAL) -->
            <div class="form-group">
                <label for="visual_format">20. Formato Visual</label>
                <input type="text" id="visual_format" name="visual_format">
            </div>

            <!-- 21. Carga de RE/Enlace (OBLIGATORIO) -->
            <div class="form-group">
                <label for="resource_type">
                    21. Carga de RE/Enlace 
                    <span class="info-icon">
                        ?<span class="tooltip">Puede subir un archivo y/o proporcionar un enlace del recurso educativo</span>
                    </span>
                </label>
                <div id="file-input-section" class="file-input-wrapper">
                    <input type="file" id="resource_file" name="resource_file" accept=".html,.pdf,.mp4,.mp3,.ppt,.pptx,.doc,.docx,.jpg,.png,.exe,.apk" class="form-control">
                    <span class="file-description">Formatos permitidos: HTML, PDF, MP4, MP3, PPT, PPTX, DOC, DOCX, JPG, PNG, EXE, APK</span>
                </div>
                <div id="link-input-section" class="link-input-wrapper">
                    <input type="url" id="resource_link" name="resource_link" class="form-control" placeholder="https://">
                    <span class="link-description">Ingrese la URL del recurso educativo</span>
                </div>
            </div>

            <!-- 22. Imagen/Portada (OBLIGATORIO) -->
            <div class="form-group">
                <label for="cover_image">22. Imagen/Portada</label>
                <input type="file" id="cover_image" name="cover_image" accept=".jpg,.jpeg,.png" required>
                <span class="file-description">Formatos permitidos: JPG, JPEG, PNG</span>
            </div>
        </div>
    </div>
    <div class="form-group terms-conditions-group">
        <label class="checkbox-label">
            <input type="checkbox" id="terms_conditions" name="terms_conditions" required>
            <span class="checkbox-text">Al aceptar los términos y condiciones, el usuario brindará su aprobación en la publicación del RE</span>
        </label>
    </div>
    <button type="submit" name="submit_resource" id="submit-button">
        <span class="spinner" style="display: none;">
            <div class="loading-spinner"></div>
        </span>
        <span class="button-text">Enviar Recurso</span>
    </button>
</form>
<div id="confirmation-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <h2>¡Éxito!</h2>
        <p>Su propuesta ha sido enviada correctamente.</p>
        <button id="modal-close" class="button">Regresar al inicio</button>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
var fieldDescriptions = {"title":"Nombre completo del recurso educativo","subtitle":"Subtítulo o nombre alternativo del recurso","description":"Descripción detallada del recurso","category":"Tipo de recurso según su uso principal","skills_competencies":"Habilidades y competencias que se desarrollan con este recurso","knowledge_area":"Área de Conocimiento principal","knowledge_area_other_countries":"Áreas de conocimiento similares en otros países","language":"Idioma o lenguas en que está disponible el recurso","nivel_escolar":"Nivel escolar al que está dirigido","level_other_countries":"Denominación del nivel en otros países","age":"Edad (si aplica)","target_user":"Usuarios a los que se dirige el recurso","license":"Tipo de licencia del recurso","author":"Nombre del autor o autores","author_email":"Correo electrónico del autor","country":"País de origen","origin":"Procedencia o lugar de publicación","publication_date":"Fecha de publicación del recurso","file_type":"Tipo de archivo (e.g. pdf, jpg, mp4)","visual_format":"Formato visual o modo de interacción","resource_type":"Permite subir un archivo y/o proporcionar un enlace","cover_image":"Imagen de portada del recurso"};
$('.info-icon').each(function() {
    var fieldId = $(this).closest('.form-group').find('label').attr('for');
    var tooltipText = fieldDescriptions[fieldId] || '';
    $(this).find('.tooltip').text(tooltipText);
});
$('input[name="resource_type"]').change(function(){
    if ($(this).val() === 'file'){
        $('#file-input-section').show();
        $('#link-input-section').hide();
        $('#resource_link').prop('required', false);
        $('#resource_file').prop('required', true);
    } else {
        $('#file-input-section').hide();
        $('#link-input-section').show();
        $('#resource_link').prop('required', true);
        $('#resource_file').prop('required', false);
    }
});
$('.dropdown-header').on('click', function(){
    $('#skillsDropdown').toggleClass('show');
    $(this).toggleClass('active');
});
document.addEventListener('click', function(event){
    if (!event.target.closest('.dropdown-checkbox')){
        $('#skillsDropdown').removeClass('show');
        $('.dropdown-header').removeClass('active');
    }
});
$('input[name="skills_competencies[]"]').on('change', function(){
    var count = $('input[name="skills_competencies[]"]:checked').length;
    var header = $('.dropdown-header');
    header.text(count > 0 ? count + ' competencias seleccionadas' : 'Seleccionar habilidades y competencias');
    header.append($('<span>').text(' ▼').addClass('dropdown-arrow'));

    if(count > 0) {
        $('#skills_dropdown_error').remove();
    }
});
$('.form-group input, .form-group select, .form-group textarea').each(function(){
    if($(this).val().trim()){ $(this).next('.error-message').remove(); }
    $(this).on('input change', function(){
        if($(this).val().trim()){
            $(this).next('.error-message').remove();
        }
    });
});
var tags = [];
function updateTags(){
    $('#tags-container').html('');
    tags.forEach(function(tag){
        var tagEl = $('<span class="tag">'+tag+'<span class="tag-remove">×</span></span>');
        tagEl.find('.tag-remove').click(function(){
            tags = tags.filter(function(t){ return t !== tag; });
            updateTags();
            $('#knowledge_area_other_countries').val(tags.join(','));
        });
        $('#tags-container').append(tagEl);
    });
}
$('#add-tag-btn').on('click', function(e){
    e.preventDefault();
    var value = $('#knowledge_area_other_countries_input').val().trim();
    if (value && tags.indexOf(value) === -1){
        tags.push(value);
        updateTags();
        $('#knowledge_area_other_countries').val(tags.join(','));
        $('#knowledge_area_other_countries_input').val('');
    }
});
$('#knowledge_area_other_countries_input').on('keypress', function(e){
    if(e.key === 'Enter'){
        e.preventDefault();
        var value = $(this).val().trim();
        if (value && tags.indexOf(value) === -1){
            tags.push(value);
            updateTags();
            $('#knowledge_area_other_countries').val(tags.join(', '));
            $(this).val('');
        }
    }
});
var languageTags = [];
function updateLanguageTags(){
    $('#language-tags-container').html('');
    languageTags.forEach(function(tag){
        var tagEl = $('<span class="tag">'+tag+'<span class="tag-remove">×</span></span>');
        tagEl.find('.tag-remove').click(function(){
            languageTags = languageTags.filter(function(t){ return t !== tag; });
            updateLanguageTags();
            $('#language').val(languageTags.join(','));
        });
        $('#language-tags-container').append(tagEl);
    });
}
$('#add-language-btn').on('click', function(e){
    e.preventDefault();
    var value = $('#language_input').val().trim();
    if (value && languageTags.indexOf(value) === -1){
        languageTags.push(value);
        updateLanguageTags();
        $('#language').val(languageTags.join(','));
        $('#language_input').val('');
    }
});
$('#language_input').on('keypress', function(e){
    if(e.key === 'Enter'){
        e.preventDefault();
        var value = $(this).val().trim();
        if (value && languageTags.indexOf(value) === -1){
            languageTags.push(value);
            updateLanguageTags();
            $('#language').val(languageTags.join(','));
            $(this).val('');
        }
    }
});
var isSubmitting = false;
$('#educational-resource-form').on('submit', function(e){
    e.preventDefault();
    if (isSubmitting) { return false; }
    $('.error-message').remove();
    var valid = true;
    $('#educational-resource-form [required]').each(function(){
        if (!$(this).val().trim()){
            valid = false;
            $(this).after('<span class="error-message">Este campo es requerido</span>');
        }
    });
    if ($('input[name="skills_competencies[]"]:checked').length === 0) {
        valid = false;
        if (!$('#skills_dropdown_error').length) {
            $('.dropdown-checkbox').append('<span id="skills_dropdown_error" class="error-message">Debe seleccionar al menos una opción</span>');
        }
    } else {
        $('#skills_dropdown_error').remove();
    }
    if (!valid) {
        var firstError = $('#educational-resource-form .error-message').first().prev();
        if(firstError.length){
            $('html, body').animate({scrollTop: firstError.offset().top - 20}, 500);
        }
        return false;
    }
    var selectedSkills = [];
    $('input[name="skills_competencies[]"]:checked').each(function(){
        selectedSkills.push($(this).val());
    });
    if (!$('#skills_competencies_hidden').length) {
        $('<input>').attr({ type: 'hidden', id: 'skills_competencies_hidden', name: 'skills_competencies' }).appendTo(this);
    }
    $('#skills_competencies_hidden').val(selectedSkills.join(', '));
    var $form = $(this);
    var $submitButton = $('#submit-button');
    var $spinner = $submitButton.find('.spinner');
    var $buttonText = $submitButton.find('.button-text');
    isSubmitting = true;
    $submitButton.prop('disabled', true);
    $spinner.show();
    $buttonText.text('Enviando...');
    var formData = new FormData(this);
    var fileInput = document.getElementById('resource_file');
    if(fileInput && fileInput.files.length > 0){
        formData.append('resource_file', fileInput.files[0]);
    }
    formData.append('action', 'submit_resource');
    formData.append('nonce', $('#nonce').val());
    formData.append('skills_competencies', selectedSkills.join(','));
    $.ajax({
        url: ajaxurl,
        type: 'POST',
        dataType: 'json',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response){
            if (response.success) {
                $('#confirmation-modal').fadeIn();
                $form[0].reset();
                tags = [];
                languageTags = [];
                updateTags();
                updateLanguageTags();
            } else {
                alert(response.message || 'Error al enviar el recurso');
            }
        },
        error: function(xhr, status, error){
            alert('Hubo un error al procesar su solicitud: ' + error);
        },
        complete: function(){
            isSubmitting = false;
            $submitButton.prop('disabled', false);
            $spinner.hide();
            $buttonText.text('Enviar Recurso');
        }
    });
});
$('#modal-close').on('click', function(){
    $('#confirmation-modal').fadeOut(function(){
        window.location.href = '/';
    });
});
});
</script>