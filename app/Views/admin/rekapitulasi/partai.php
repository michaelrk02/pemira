<div class="container">
    <div style="margin: 1rem">
        <table class="striped highlight responsive-table">
            <thead>
                <tr>
                    <th>No Urut</th>
                    <th>Nama</th>
                    <th>Perolehan Suara</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listPartai as $i => $partai): ?>
                    <tr>
                        <td><?php echo $partai->obj->ID; ?></td>
                        <td><?php echo esc($partai->obj->Nama); ?></td>
                        <td><?php echo $partai->jumlah; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
