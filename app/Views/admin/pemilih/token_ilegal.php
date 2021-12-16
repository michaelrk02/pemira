<p><b>List Token Ilegal (<?php echo count($tokenIlegal); ?>):</b></p>
<ul>
    <?php foreach ($tokenIlegal as $token): ?>
        <li><b style="font-family: monospace"><?php echo $token->token; ?></b> (IDCapres: <?php echo $token->idcapres; ?>, IDCaleg: <?php echo $token->idcaleg; ?>)</li>
    <?php endforeach; ?>
</ul>
<p><b>IDCapres ilegal</b></p>
<ul>
    <?php foreach ($capresIlegal as $idcapres => $jumlah): ?>
        <li><b>ID <?php echo $idcapres; ?></b> (terdapat <?php echo $jumlah; ?> suara ilegal)</li>
    <?php endforeach; ?>
</ul>
<p><b>IDCaleg ilegal</b></p>
<ul>
    <?php foreach ($calegIlegal as $idcaleg => $jumlah): ?>
        <li><b>ID <?php echo $idcaleg; ?></b> (terdapat <?php echo $jumlah; ?> suara ilegal)</li>
    <?php endforeach; ?>
</ul>
