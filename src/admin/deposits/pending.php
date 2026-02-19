<?php
$deposits = $deposits ?? [];
$success = $_SESSION['flash_success'] ?? '';
$error = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-dark text-white">
    <nav class="navbar navbar-dark bg-black">
        <div class="container-fluid">
            <a class="navbar-brand" href="/admin">Administration</a>
            <a href="/dashboard" class="btn btn-outline-light btn-sm">Retour au site</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Dépôts en attente</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (empty($deposits)): ?>
            <div class="alert alert-info">Aucun dépôt en attente.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilisateur</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th>Capture</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($deposits as $deposit): ?>
                        <tr>
                            <td>#<?= $deposit['id'] ?></td>
                            <td>
                                <?= htmlspecialchars($deposit['username'] ?? $deposit['email']) ?><br>
                                <small>ID: <?= $deposit['user_id'] ?></small>
                            </td>
                            <td>$<?= number_format($deposit['amount'], 2) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($deposit['created_at'])) ?></td>
                            <td>
                                <a href="<?= htmlspecialchars($deposit['screenshot']) ?>" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-image"></i> Voir
                                </a>
                            </td>
                            <td>
                                <form action="/admin/deposits/approve/<?= $deposit['id'] ?>" method="POST" style="display:inline;">
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approuver ce dépôt et créditer le compte ?')">
                                        <i class="fas fa-check"></i> Approuver
                                    </button>
                                </form>
                                <form action="/admin/deposits/reject/<?= $deposit['id'] ?>" method="POST" style="display:inline;">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Refuser ce dépôt ?')">
                                        <i class="fas fa-times"></i> Refuser
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>