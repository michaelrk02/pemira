<div style="margin-bottom: 2rem">
    <h4><?php echo $totalPemilih; ?> suara dari total <?php echo $totalKuota; ?> DPT telah masuk (<?php echo ($totalKuota == 0) ? 0 : round((double)$totalPemilih / $totalKuota * 100.0, 2); ?>%)</h4>
</div>
<div>
    <table class="striped responsive-table">
        <thead>
            <th>Program Studi</th>
            <th>Suara Masuk</th>
            <th>Kuota Pemilih</th>
            <th>Persentase</th>
        </thead>
        <tbody>
            <?php foreach ($sebaranPemilih as $pemilihProdi): ?>
                <tr>
                    <td><?php echo esc($pemilihProdi->nama); ?></td>
                    <td><?php echo $pemilihProdi->pemilih; ?></td>
                    <td><?php echo $pemilihProdi->kuota; ?></td>
                    <td><?php echo ($pemilihProdi->kuota == 0) ? 0 : round((double)$pemilihProdi->pemilih / $pemilihProdi->kuota * 100.0, 2); ?>%</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

