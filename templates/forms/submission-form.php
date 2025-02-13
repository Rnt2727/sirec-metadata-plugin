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
                <label for="description">9. Descripción</label>
                <textarea id="description" name="description"></textarea>
            </div>
            <div class="form-group">
                <label for="publication_date">10. Fecha de Publicación</label>
                <input type="date" id="publication_date" name="publication_date">
            </div>
            <div class="form-group">
                <label for="language">11. Idioma</label>
                <input type="text" id="language" name="language">
            </div>
        </div>
        
       
        <div class="form-column">
            <div class="form-group">
                <label for="school_sequence">12. Secuencia Escolar</label>
                <input type="text" id="school_sequence" name="school_sequence">
            </div>
            <div class="form-group">
                <label for="level_other_countries">13. Nivel en Otros Países</label>
                <input type="text" id="level_other_countries" name="level_other_countries">
            </div>
            <div class="form-group">
                <label for="file_type">14. Tipo de Archivo</label>
                <input type="text" id="file_type" name="file_type">
            </div>
            <div class="form-group">
                <label for="visual_format">15. Formato Visual</label>
                <input type="text" id="visual_format" name="visual_format">
            </div>
            <div class="form-group">
                <label for="target_user">16. Usuario Destinatario</label>
                <input type="text" id="target_user" name="target_user">
            </div>
            <div class="form-group">
                <label for="skills_competencies">17. Habilidades y Competencias</label>
                <input type="text" id="skills_competencies" name="skills_competencies">
            </div>
            <div class="form-group">
                <label for="license">18. Licencia</label>
                <input type="text" id="license" name="license">
            </div>
            <div class="form-group">
                <label for="cab_rating">19. Calificación CAB</label>
                <input type="text" id="cab_rating" name="cab_rating">
            </div>
            <div class="form-group">
                <label for="cab_seal">20. Sello CAB</label>
                <input type="text" id="cab_seal" name="cab_seal">
            </div>
        </div>
    </div>
    
    <button type="submit" name="submit_resource">Enviar Recurso</button>
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
        'language': 'Idioma principal del recurso',
        'school_sequence': 'Nivel educativo al que está dirigido',
        'level_other_countries': 'Equivalencia del nivel educativo en otros países',
        'file_type': 'Formato del archivo (PDF, DOC, etc.)',
        'visual_format': 'Formato visual del recurso',
        'target_user': 'Usuario final al que está dirigido el recurso',
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
    $('#educational-resource-form').on('submit', function(e) {
        e.preventDefault();
        
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
                    $('#educational-resource-form')[0].reset();
                    // Limpiar los tags también
                    tags = [];
                    updateTags();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Hubo un error al procesar su solicitud.');
            }
        });
    });
    
    $('#modal-close').on('click', function() {
        $('#confirmation-modal').hide();
        window.location.href = '/';
    });
});
</script>