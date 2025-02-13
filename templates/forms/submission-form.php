<form method="post" class="educational-resource-form">
    <div class="form-group">
        <label>Title *</label>
        <input type="text" name="title" required>
    </div>
    <div class="form-group">
        <label>Subtitle</label>
        <input type="text" name="subtitle">
    </div>
    <div class="form-group">
        <label>Category *</label>
        <select name="category" required>
            <option value="">Select Category</option>
            <option value="consultation">Resource for Consultation</option>
            <option value="teacher_support">Teacher Support Resource</option>
            <option value="complementary">Complementary Didactic Resource</option>
            <option value="ludic">Ludic Resource</option>
            <option value="student_work">Student Work Resource</option>
        </select>
    </div>
    <div class="form-group">
        <label>Author *</label>
        <input type="text" name="author" required>
    </div>
    <div class="form-group">
        <label>Author Email *</label>
        <input type="email" name="author_email" required>
    </div>
    <div class="form-group">
        <label>Origin</label>
        <input type="text" name="origin">
    </div>
    <div class="form-group">
        <label>Country</label>
        <input type="text" name="country">
    </div>
    <div class="form-group">
        <label>Knowledge Area</label>
        <input type="text" name="knowledge_area">
    </div>
    <div class="form-group">
        <label>Knowledge Area (Other)</label>
        <input type="text" name="knowledge_area_other">
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea name="description"></textarea>
    </div>
    <div class="form-group">
        <label>Publication Date</label>
        <input type="date" name="publication_date">
    </div>
    <div class="form-group">
        <label>Language</label>
        <input type="text" name="language">
    </div>
    <div class="form-group">
        <label>School Sequence</label>
        <input type="text" name="school_sequence">
    </div>
    <div class="form-group">
        <label>Level Other Countries</label>
        <input type="text" name="level_other_countries">
    </div>
    <div class="form-group">
        <label>File Type</label>
        <input type="text" name="file_type">
    </div>
    <div class="form-group">
        <label>Visual Format</label>
        <input type="text" name="visual_format">
    </div>
    <div class="form-group">
        <label>Target User</label>
        <input type="text" name="target_user">
    </div>
    <div class="form-group">
        <label>Skills Competencies</label>
        <input type="text" name="skills_competencies">
    </div>
    <div class="form-group">
        <label>License</label>
        <input type="text" name="license">
    </div>
    <div class="form-group">
        <label>CAB Rating</label>
        <input type="text" name="cab_rating">
    </div>
    <div class="form-group">
        <label>CAB Seal</label>
        <input type="text" name="cab_seal">
    </div>
    <button type="submit" name="submit_resource">Submit Resource</button>
</form>