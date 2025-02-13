// templates/submission-form.php
<form method="post" class="educational-resource-form">
    <?php wp_nonce_field('submit_educational_resource', 'educational_resource_nonce'); ?>
    
    <div class="form-group">
        <label>Título del recurso</label>
        <input type="text" name="title" required>
    </div>

    <div class="form-group">
        <label>Subtítulo</label>
        <input type="text" name="subtitle">
    </div>

    <div class="form-group">
        <label>Categoría</label>
        <select name="category" required>
            <option value="consultation">Recurso de Consulta</option>
            <option value="teacher_support">Recurso de apoyo para docentes</option>
            <option value="complementary">Recurso didáctico complementario</option>
            <option value="ludic">Recurso Lúdico</option>
            <option value="student_work">Recurso de trabajo para los estudiantes</option>
        </select>
    </div>

    <div class="form-group">
        <label>Autor</label>
        <input type="text" name="author" required>
    </div>

    <div class="form-group">
        <label>Correo del autor</label>
        <input type="email" name="author_email" required>
    </div>

    <div class="form-group">
        <label>Procedencia</label>
        <input type="text" name="origin" required>
    </div>

    <div class="form-group">
        <label>País</label>
        <input type="text" name="country" required>
    </div>

    <div class="form-group">
        <label>Área de Conocimiento</label>
        <input type="text" name="knowledge_area" required>
    </div>

    <div class="form-group">
        <label>Área de conocimiento en otros países</label>
        <input type="text" name="knowledge_area_other">
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <textarea name="description" required></textarea>
    </div>

    <div class="form-group">
        <label>Fecha de publicación</label>
        <input type="date" name="publication_date" required>
    </div>

    <div class="form-group">
        <label>Última actualización</label>
        <input type="date" name="last_update">
    </div>

    <div class="form-group">
        <label>Idioma</label>
        <input type="text" name="language" required>
    </div>

    <div class="form-group">
        <label>Secuencia escolar y edad</label>
        <input type="text" name="school_sequence" required>
    </div>

    <div class="form-group">
        <label>Denominación de nivel en otros países</label>
        <input type="text" name="level_other_countries">
    </div>

    <div class="form-group">
        <label>Tipo de archivo</label>
        <input type="text" name="file_type" required>
    </div>

    <div class="form-group">
        <label>Formato visual</label>
        <input type="text" name="visual_format" required>
    </div>

    <div class="form-group">
        <label>Usuario al que está dirigido</label>
        <input type="text" name="target_user" required>
    </div>

    <div class="form-group">
        <label>Destrezas y habilidades</label>
        <input type="text" name="skills" required>
    </div>

    <div class="form-group">
        <label>Licencia</label>
        <input type="text" name="license" required>
    </div>

    <button type="submit">Enviar recurso</button>
</form>