<form method="post" class="educational-resource-form">
    <?php wp_nonce_field('submit_resource_form', 'resource_form_nonce'); ?>
    <h2>Enviar Recurso Educativo</h2>
    
    <div class="form-columns">
        <!-- Columna 1 -->
        <div class="form-column">
            <div class="form-group">
                <label for="title">1. Título *</label>
                <input type="text" id="title" name="title" required>
            </div>
            
            <div class="form-group">
                <label for="subtitle">2. Subtítulo</label>
                <input type="text" id="subtitle" name="subtitle">
            </div>
            
            <div class="form-group">
                <label for="category">3. Categoría *</label>
                <select id="category" name="category" required>
                    <option value="">Seleccionar Categoría</option>
                    <option value="consultation">Recurso para Consulta</option>
                    <option value="teacher_support">Recurso de Apoyo para Docentes</option>
                    <option value="complementary">Recurso Didáctico Complementario</option>
                    <option value="ludic">Recurso Lúdico</option>
                    <option value="student_work">Recurso para Trabajo Estudiantil</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="author">4. Autor *</label>
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
            
            <div class="form-group">
                <label for="knowledge_area">8. Área de Conocimiento</label>
                <input type="text" id="knowledge_area" name="knowledge_area">
            </div>
            
            <div class="form-group">
                <label for="knowledge_area_other">9. Área de Conocimiento (Otro)</label>
                <input type="text" id="knowledge_area_other" name="knowledge_area_other">
            </div>
        </div>
        
        <!-- Columna 2 -->
        <div class="form-column">
            <div class="form-group">
                <label for="description">10. Descripción</label>
                <textarea id="description" name="description"></textarea>
            </div>
            
            <div class="form-group">
                <label for="publication_date">11. Fecha de Publicación</label>
                <input type="date" id="publication_date" name="publication_date">
            </div>
            
            <div class="form-group">
                <label for="language">12. Idioma</label>
                <input type="text" id="language" name="language">
            </div>
            
            <div class="form-group">
                <label for="school_sequence">13. Secuencia Escolar</label>
                <input type="text" id="school_sequence" name="school_sequence">
            </div>
            
            <div class="form-group">
                <label for="level_other_countries">14. Nivel en Otros Países</label>
                <input type="text" id="level_other_countries" name="level_other_countries">
            </div>
            
            <div class="form-group">
                <label for="file_type">15. Tipo de Archivo</label>
                <input type="text" id="file_type" name="file_type">
            </div>
            
            <div class="form-group">
                <label for="visual_format">16. Formato Visual</label>
                <input type="text" id="visual_format" name="visual_format">
            </div>
            
            <div class="form-group">
                <label for="target_user">17. Usuario Destinatario</label>
                <input type="text" id="target_user" name="target_user">
            </div>
            
            <div class="form-group">
                <label for="skills_competencies">18. Habilidades y Competencias</label>
                <input type="text" id="skills_competencies" name="skills_competencies">
            </div>
            
            <div class="form-group">
                <label for="license">19. Licencia</label>
                <input type="text" id="license" name="license">
            </div>
            
            <div class="form-group">
                <label for="cab_rating">20. Calificación CAB</label>
                <input type="text" id="cab_rating" name="cab_rating">
            </div>
            
            <div class="form-group">
                <label for="cab_seal">21. Sello CAB</label>
                <input type="text" id="cab_seal" name="cab_seal">
            </div>
        </div>
    </div>
    
    <button type="submit" name="submit_resource">Enviar Recurso</button>
</form>