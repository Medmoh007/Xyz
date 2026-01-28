<h2>Utilisateurs</h2>

<table border="1" cellpadding="5">
<?php foreach ($users as $user): ?>
<tr>
    <td><?= $user['name'] ?></td>
    <td><?= $user['email'] ?></td>
    <td><?= $user['role'] ?></td>
</tr>
<?php endforeach; ?>
</table>
