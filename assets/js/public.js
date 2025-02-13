// assets/js/public.js
jQuery(document).ready(function($) {
    $('.educational-resource-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'submit_educational_resource',
                formData: formData
            },
            success: function(response) {
                alert('Recurso enviado exitosamente');
                location.reload();
            },
            error: function() {
                alert('Error al enviar el recurso');
            }
        }); 
    });
});