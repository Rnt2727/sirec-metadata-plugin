// assets/js/admin.js
jQuery(document).ready(function($) {
    $('.view-details').on('click', function(e) {
        e.preventDefault();
        var resourceId = $(this).data('id');
        // Implementar vista detallada
    });

    $('.approve-resource').on('click', function(e) {
        e.preventDefault();
        var resourceId = $(this).data('id');
        // Implementar aprobación
    });

    $('.reject-resource').on('click', function(e) {
        e.preventDefault();
        var resourceId = $(this).data('id');
        // Implementar rechazo
    });
});