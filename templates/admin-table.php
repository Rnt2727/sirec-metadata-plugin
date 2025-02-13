// templates/admin-page.php
<div class="wrap">
    <h1>Recursos Educativos</h1>
    
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Título</th>
                <th>Categoría</th>
                <th>Autor</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resources as $resource): ?>
            <tr>
                <td><?php echo esc_html($resource->title); ?></td>
                <td><?php echo esc_html($resource->category); ?></td>
                <td><?php echo esc_html($resource->author); ?></td>
                <td><?php echo esc_html($resource->status); ?></td>
                <td><?php echo esc_html($resource->created_at); ?></td>
                <td>
                    <a href="#" class="button view-details" data-id="<?php echo $resource->id; ?>">Ver detalles</a>
                    <a href="#" class="button approve-resource" data-id="<?php echo $resource->id; ?>">Aprobar</a>
                    <a href="#" class="button reject-resource" data-id="<?php echo $resource->id; ?>">Rechazar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>