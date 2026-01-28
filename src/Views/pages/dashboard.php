<h1>Dashboard</h1>

<p><strong>Solde :</strong> <?= $balance ?> €</p>

<h3>Investissements</h3>
<ul>
<?php foreach ($investments as $inv): ?>
    <li><?= $inv['amount'] ?> € (<?= $inv['plan'] ?>)</li>
<?php endforeach; ?>
</ul>
