<div class="container">
    <div style="margin-top: 2rem; margin-bottom: 2rem">
        <a class="btn" href="<?php echo site_url('admin/resource/create'); ?>"><i class="fa fa-plus left"></i> CREATE RESOURCE</a>
    </div>
    <table class="striped highlight table-responsive" style="margin-top: 2rem; margin-bottom: 2rem">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Size</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resources as $resource): ?>
                <tr>
                    <td><?php echo esc($resource['name']); ?></td>
                    <td><?php echo esc($resource['mime']); ?></td>
                    <td><?php echo esc($resource['size']); ?></td>
                    <td>
                        <a class="btn" target="_blank" href="<?php echo site_url('admin/resource/view').'?id='.urlencode($resource['name']); ?>"><i class="fa fa-eye left"></i> VIEW</a>
                        <a class="btn red" href="<?php echo site_url('admin/resource/drop').'?id='.urlencode($resource['name']); ?>" onclick="return confirm('Are you sure?')"><i class="fa fa-trash left"></i> DROP</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
