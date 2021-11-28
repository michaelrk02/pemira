<p><b>List Token Ilegal (<?php echo count($tokenIlegal); ?>):</b></p>
<ul>
    <?php foreach ($tokenIlegal as $token): ?>
        <li><?php echo $token->token; ?></li>
    <?php endforeach; ?>
</ul>
