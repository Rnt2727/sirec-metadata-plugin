<form id="educational-resource-form" class="educational-resource-form">
    <?php wp_nonce_field('submit_resource_form', 'resource_form_nonce'); ?>
    <h2>Propuesta de Recurso Educativo</h2>
    
    <div class="form-columns">
        <div class="form-column">
            <div class="form-group">
                <label for="title">1. Título del recurso</label>
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
                <label for="author">4. Autor</label>
                <input type="text" id="author" name="author" required>
            </div>
            <div class="form-group">
                <label for="author_email">5. Correo del Autor *</label>
                <input type="email" id="author_email" name="author_email" required>
            </div>
            <div class="form-group">
                <label for="origin">6. Origen</label>
                <input type="text" id="origin" name="origin">
            </div>
            <div class="form-group">
                <label for="country">7. País</label>
                <input type="text" id="country" name="country">
            </div>
        </div>
        
        <div class="form-column">
            <div class="form-group">
                <label for="knowledge_area">8. Área de Conocimiento</label>
                <input type="text" id="knowledge_area" name="knowledge_area">
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
        </div>

        <div class="form-column">
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
    function checkIfFilled(element) {
        if (element.value.trim() !== '') {
            element.classList.add('filled');
        } else {
            element.classList.remove('filled');
        }
    }

    const formFields = document.querySelectorAll('.form-group input, .form-group select, .form-group textarea');
    
    formFields.forEach(field => {
        // Verificar estado inicial
        checkIfFilled(field);
        
        // Verificar en cada cambio
        field.addEventListener('input', function() {
            checkIfFilled(this);
        });
        
        field.addEventListener('change', function() {
            checkIfFilled(this);
        });
        
        // Para select, verificar también cuando se selecciona una opción
        if (field.tagName === 'SELECT') {
            field.addEventListener('change', function() {
                checkIfFilled(this);
            });
        }
    });


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
        window.location.href = '/'; // Redirigir al inicio
    });
});
</script>