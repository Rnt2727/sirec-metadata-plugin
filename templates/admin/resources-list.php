<?php
$db = new ERM_Database();
$resources = $db->get_resources();
?>
<div class="wrap">
    <h1>Educational Resources</h1>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Email</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php if($resources && count($resources) > 0): ?>
                <?php foreach ($resources as $resource): ?>
                    <tr>
                        <td><?php echo esc_html($resource->id); ?></td>
                        <td><?php echo esc_html($resource->title); ?></td>
                        <td><?php echo esc_html($resource->author); ?></td>
                        <td><?php echo esc_html($resource->category); ?></td>
                        <td><?php echo esc_html($resource->author_email); ?></td>
                        <td><?php echo esc_html($resource->description); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No hay recursos disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>