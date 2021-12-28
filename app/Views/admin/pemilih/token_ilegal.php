<p>Token ilegal adalah token yang memiliki salah satu atau lebih kriteria: <b>tidak normal</b>, <b>tidak valid</b>, atau <b>fiktif</b>. Panitia dapat mengurangi perolehan suara untuk calon yang terdampak berdasarkan data dari halaman ini</p>
<p><b>IDCapres ilegal:</b></p>
<ul>
    <?php foreach ($capresIlegal as $idcapres => $jumlah): ?>
        <li><b>ID <?php echo $idcapres; ?></b> (terdapat <?php echo $jumlah; ?> suara ilegal)</li>
    <?php endforeach; ?>
</ul>
<p><b>IDCaleg ilegal:</b></p>
<ul>
    <?php foreach ($calegIlegal as $idcaleg => $jumlah): ?>
        <li><b>ID <?php echo $idcaleg; ?></b> (terdapat <?php echo $jumlah; ?> suara ilegal)</li>
    <?php endforeach; ?>
</ul>
<p><b>List token ilegal (<?php echo count($tokenIlegal); ?>):</b></p>
<ul>
    <?php foreach ($tokenIlegal as $token): ?>
        <li><b style="font-family: monospace"><?php echo $token->token; ?></b> (IDCapres: <?php echo $token->idcapres; ?>, IDCaleg: <?php echo $token->idcaleg; ?>)</li>
    <?php endforeach; ?>
</ul>
