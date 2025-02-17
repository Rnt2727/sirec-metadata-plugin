<form id="educational-resource-form" class="educational-resource-form">
    <?php wp_nonce_field('submit_resource_form', 'resource_form_nonce'); ?>
    <h2>Propuesta de Recurso Educativo</h2>
    
    <div class="form-columns">
        <div class="form-column">
            <div class="form-group">
                <label for="title">
                    1. Título del recurso
                    <span class="info-icon">?
                        <span class="tooltip"></span>
                    </span>
                </label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="subtitle">2. Subtítulo</label>
                <input type="text" id="subtitle" name="subtitle" required>
            </div>
            <div class="form-group">
                <label for="category">3. Categoría</label>
                <select id="category" name="category" required>
                    <option value="">Seleccionar Categoría</option>
                    <option value="RC">R.C. Recursos de consulta</option>
                    <option value="RAD">R.A.D. Recursos de Apoyo para Docentes</option>
                    <option value="RDC">R.D.C. Recursos Didácticos Complementarios</option>
                    <option value="RL">R.L. Recursos Lúdicos</option>
                    <option value="RTE">R.T.E. Recursos de Trabajo para el Estudiante</option>
                </select>
            </div>
            <div class="form-group">
                <label for="author">4. Autor
                    <span class="info-icon">?
                        <span class="tooltip"></span>
                    </span>
                </label>
                <input type="text" id="author" name="author" required>
            </div>
            <div class="form-group">
                <label for="author_email">5. Correo del Autor</label>
                <input type="email" id="author_email" name="author_email" required>
            </div>
            <div class="form-group">
                <label for="origin">6. Procedencia / Lugar de publicación
                    <span class="info-icon">?
                            <span class="tooltip"></span>
                    </span>
                </label>
                
                <input type="text" id="origin" name="origin" required>
            </div>
            <div class="form-group">
                <label for="country">7. País</label>
                <input type="text" id="country" name="country" required>
            </div>
            <div class="form-group">
                <label for="knowledge_area">8. Área de Conocimiento</label>
                <input type="text" id="knowledge_area" name="knowledge_area" required>
            </div>
            <div class="form-group">
                <label for="knowledge_area_other_countries">9. Área de conocimiento en otros Países
                    <span class="info-icon">?
                        <span class="tooltip"></span>
                    </span>
                </label>
                <div class="tags-input-container">
                    <div class="input-button-group">
                        <input type="text" id="knowledge_area_other_countries_input" placeholder="Escribe el área de conocimiento">
                        <button type="button" id="add-tag-btn" class="add-tag-button">Agregar</button>
                    </div>
                    <div id="tags-container"></div>
                    <input type="hidden" id="knowledge_area_other_countries" name="knowledge_area_other_countries" required>
                </div>
            </div>
            <div class="form-group">
                <label for="description">10. Descripción</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            
        </div>
        
       
        <div class="form-column">
            
            <div class="form-group">
                <label for="publication_date">11. Fecha de Publicación</label>
                <input type="date" id="publication_date" name="publication_date" required>
            </div>
            <div class="form-group">
                <label for="language">12. Idioma
                <span class="info-icon">?
                        <span class="tooltip"></span>
                    </span>
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
            <div class="form-group two-columns">
                <div class="column">
                    <label for="school_sequence">13. Secuencia Escolar <span class="info-icon">?
                        <span class="tooltip"></span>
                    </span>
                </label></label>
                    <input type="text" id="school_sequence" name="school_sequence" required>
                </div>
                <div class="column">
                    <label for="age">Edad</label>
                    <input type="number" id="age" name="age" min="0" max="100" placeholder="Ingrese edad" required>
                </div>
            </div>
            <div class="form-group">
                <label for="level_other_countries">14. Denominación de nivel en otros países
                <span class="info-icon">?
                        <span class="tooltip"></span>
                    </span>
                </label>
                <input type="text" id="level_other_countries" name="level_other_countries" required>
            </div>
            <div class="form-group">
                <label for="file_type">Tipo de archivo (pdf, jpg, png, mp4, otros)</label>
                <input type="text" id="file_type" name="file_type" required>
            </div>
            <div class="form-group">
                <label for="resource_file">
                    15. Archivo del recurso
                    <span class="info-icon">?
                        <span class="tooltip">16. Seleccione el archivo del recurso educativo</span>
                    </span>
                </label>
                <div class="file-input-wrapper">
                    <input type="file" 
                        id="resource_file" 
                        name="resource_file" 
                        accept=".html,.pdf,.mp4,.mp3,.ppt,.pptx,.doc,.docx,.jpg,.png,.exe,.apk" 
                        required
                        class="form-control">
                    <span class="file-description">Formatos permitidos: HTML, PDF, MP4, MP3, PPT, PPTX, DOC, DOCX, JPG, PNG, EXE, APK</span>
                </div>
            </div>

            <div class="form-group">
                <label for="license">
                    Licencia
                    <span class="info-icon">?
                        <span class="tooltip">Seleccione el tipo de licencia para su recurso</span>
                    </span>
                </label>
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
            <div class="form-group">
                <label for="visual_format">18. Formato Visual
                <span class="info-icon">?
                        <span class="tooltip"></span>
                    </span>
                </label>
                <input type="text" id="visual_format" name="visual_format" required>
            </div>
            <div class="form-group">
                <label for="target_user">19. Usuario al que está dirigido
                <span class="info-icon">?
                        <span class="tooltip"></span>
                    </span>
                </label>
                <input type="text" id="target_user" name="target_user" required>
            </div>
            <div class="form-group">
                <label for="skills_competencies">20. Destrezas, habilidades y/o competencias principales que atiende</label>
                <input type="text" id="skills_competencies" name="skills_competencies" required>
            </div>
            
        </div>
    </div>
    
    <button type="submit" name="submit_resource" id="submit-button">
        <span class="spinner" style="display: none;">
            <div class="loading-spinner"></div>
        </span>
        <span class="button-text">Enviar Recurso</span>
    </button>
</form>



<script>
jQuery(document).ready(function($) {
    /*Tooltip inicio*/
    const fieldDescriptions = {
        'title': 'Nombre completo del recurso educativo',
        'subtitle': 'Subtítulo o nombre alternativo del recurso',
        'category': 'Tipo de recurso según su uso principal',
        'author': 'Persona que figure como creador o elaborador del recurso educativo y que tenga el derecho de autor.',
        'author_email': 'Correo electrónico de contacto del autor',
        'origin': 'Es en nombre del país u organización internacional de procedencia del RE',
        'country': 'País de origen del recurso',
        'knowledge_area': 'Es la disciplina o campo del saber al que se asocia el Recurso Educativo',
        'knowledge_area_other_countries': 'Son palabras clave asociadas al contenido, temática, habilidad, destreza, competencias, etc.',
        'description': 'Descripción detallada del contenido y objetivo del recurso',
        'publication_date': 'Fecha en que se publicó el recurso',
        'language': 'Lengua/s en la que está expresado el recurso educativo',
        'school_sequence': 'Secuencia escolar, se refiere al periodo o etapa en que el estudiante adquiere una habilidad.',
        'level_other_countries': 'Equivalencia del nivel educativo en otros países',
        'file_type': 'Formato del archivo (PDF, DOC, etc.)',
        'visual_format': 'Es el modo en que el usuario accede o interactúa con el contenido del recurso educativo (audio, texto, juego, lectura, etc)',
        'target_user': 'Directivo, investigadores, docentes, padres de familia, estudiantes, etc)',
        'skills_competencies': 'Habilidades y competencias que desarrolla',
        'license': 'Tipo de licencia de uso del recurso',
        'cab_rating': 'Calificación asignada por el CAB',
        'cab_seal': 'Sello de calidad del CAB'
    };

    $('.info-icon').each(function() {
        // Obtener el ID del campo desde el atributo "for" del label
        const fieldId = $(this).closest('.form-group').find('label').attr('for');
        const tooltipText = fieldDescriptions[fieldId];
        if (tooltipText) {
            $(this).find('.tooltip').text(tooltipText);
        }
    });

    /*Tooltip final*/

    function checkIfFilled(element) {
        if (element.value.trim() !== '') {
            element.classList.add('filled');
        } else {
            element.classList.remove('filled');
        }
    }

    const formFields = document.querySelectorAll('.form-group input, .form-group select, .form-group textarea');
    
    formFields.forEach(field => {
        checkIfFilled(field);
        field.addEventListener('input', function() {
            checkIfFilled(this);
        });
        field.addEventListener('change', function() {
            checkIfFilled(this);
        });
        if (field.tagName === 'SELECT') {
            field.addEventListener('change', function() {
                checkIfFilled(this);
            });
        }
    });

    // Sistema de tags mejorado
    const tagsInput = $('#knowledge_area_other_countries_input');
    const tagsContainer = $('#tags-container');
    const hiddenInput = $('#knowledge_area_other_countries');
    const addTagBtn = $('#add-tag-btn');
    let tags = [];

    function updateTags() {
        tagsContainer.html('');
        tags.forEach(tag => {
            const tagElement = $(`
                <span class="tag">
                    ${tag}
                    <span class="tag-remove">×</span>
                </span>
            `);
            
            tagElement.find('.tag-remove').click(() => {
                tags = tags.filter(t => t !== tag);
                updateTags();
                updateHiddenInput();
            });
            
            tagsContainer.append(tagElement);
        });
    }

    function updateHiddenInput() {
        hiddenInput.val(tags.join(','));
    }

    function addTag() {
        const value = tagsInput.val().trim();
        
        if (value && !tags.includes(value)) {
            tags.push(value);
            updateTags();
            updateHiddenInput();
            tagsInput.val('');
        }
    }

    // Event listeners para tags
    addTagBtn.on('click', function(e) {
        e.preventDefault();
        addTag();
    });

    tagsInput.on('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addTag();
        }
    });

    // Manejo del formulario
    // Variable para controlar si hay un envío en proceso
    let isSubmitting = false;

    $('#educational-resource-form').on('submit', function(e) {
        e.preventDefault();
        
        // Si ya hay un envío en proceso, no hacer nada
        if (isSubmitting) {
            return false;
        }
        
        const $form = $(this);
        const $submitButton = $('#submit-button');
        const $spinner = $submitButton.find('.spinner');
        const $buttonText = $submitButton.find('.button-text');
        
        // Marcar que hay un envío en proceso
        isSubmitting = true;
        
        // Deshabilitar el botón y mostrar spinner
        $submitButton.prop('disabled', true);
        $spinner.show();
        $buttonText.text('Enviando...');
        
        var formData = new FormData(this);
        formData.append('action', 'submit_resource');
        formData.append('nonce', $('#resource_form_nonce').val());
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#confirmation-modal').show();
                    $form[0].reset();
                    // Limpiar los tags
                    tags = [];
                    languageTags = [];
                    updateTags();
                    updateLanguageTags();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Hubo un error al procesar su solicitud.');
            },
            complete: function() {
                // Restaurar el estado del botón
                isSubmitting = false;
                $submitButton.prop('disabled', false);
                $spinner.hide();
                $buttonText.text('Enviar Recurso');
            }
        });
    });
    
    $('#modal-close').on('click', function() {
        $('#confirmation-modal').hide();
        window.location.href = '/';
    });

    /*inicio tags de idioma*/
    // Sistema de tags para idiomas
    const languageInput = $('#language_input');
    const languageTagsContainer = $('#language-tags-container');
    const hiddenLanguageInput = $('#language');
    const addLanguageBtn = $('#add-language-btn');
    let languageTags = [];

    function updateLanguageTags() {
        languageTagsContainer.html('');
        languageTags.forEach(tag => {
            const tagElement = $(`
                <span class="tag">
                    ${tag}
                    <span class="tag-remove">×</span>
                </span>
            `);
            
            tagElement.find('.tag-remove').click(() => {
                languageTags = languageTags.filter(t => t !== tag);
                updateLanguageTags();
                updateHiddenLanguageInput();
            });
            
            languageTagsContainer.append(tagElement);
        });
    }

    function updateHiddenLanguageInput() {
        hiddenLanguageInput.val(languageTags.join(','));
    }

    function addLanguageTag() {
        const value = languageInput.val().trim();
        
        if (value && !languageTags.includes(value)) {
            languageTags.push(value);
            updateLanguageTags();
            updateHiddenLanguageInput();
            languageInput.val('');
        }
    }

    // Event listeners para idiomas
    addLanguageBtn.on('click', function(e) {
        e.preventDefault();
        addLanguageTag();
    });

    languageInput.on('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addLanguageTag();
        }
    });
    /**fin tags de idiomas */
});
</script>