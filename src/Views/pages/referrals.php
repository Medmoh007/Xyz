<h2>Parrainage</h2>

<p>Lien de parrainage :</p>
<code><?= $link ?></code>

<ul>
<?php foreach ($referrals as $ref): ?>
    <li>Utilisateur ID : <?= $ref['user_id'] ?></li>
<?php endforeach; ?>
</ul>
