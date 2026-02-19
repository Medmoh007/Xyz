<?php
$title = 'Dépôts en attente | Admin';
ob_start();
?>
<div class="container mt-4">
    <h1>Dépôts en attente de validation</h1>
    <?php if (empty($deposits)): ?>
        <div class="alert alert-info">Aucun dépôt en attente.</div>
    <?php else: ?>
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Preuve</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($deposits as $dep): ?>
                <tr>
                    <td>#<?= $dep['id'] ?></td>
                    <td><?= htmlspecialchars($dep['user_name']) ?></td>
                    <td><?= htmlspecialchars($dep['email']) ?></td>
                    <td>$<?= number_format($dep['amount'], 2) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($dep['created_at'])) ?></td>
                    <td>
                        <a href="<?= BASE_URL . $dep['screenshot'] ?>" target="_blank">Voir</a>
                    </td>
                    <td>
                        <form method="POST" action="<?= BASE_URL ?>/admin/deposits/approve/<?= $dep['id'] ?>" style="display:inline">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <button type="submit" class="btn btn-success btn-sm">Approuver</button>
                        </form>
                        <form method="POST" action="<?= BASE_URL ?>/admin/deposits/reject/<?= $dep['id'] ?>" style="display:inline">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Rejeter</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';