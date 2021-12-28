<div class="container">
    <div style="margin: 1rem">
        <?php foreach ($listProdiCaleg as $prodiCaleg): ?>
            <?php if (count($prodiCaleg['ranking']) > 0): ?>
                <p>Prodi <b><?php echo esc($prodiCaleg['prodi_nama']); ?></b></p>
                <table class="striped highlight responsive-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No Urut</th>
                            <th>Nama</th>
                            <th>Perolehan Suara</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prodiCaleg['ranking'] as $i => $caleg): ?>
                            <tr>
                                <td><?php echo ($i + 1); ?></td>
                                <td><?php echo ($caleg->id - $prodiCaleg['prodi_id'] * 1000); ?></td>
                                <td><?php echo esc($caleg->nama); ?></td>
                                <td><?php echo $caleg->jumlah; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
