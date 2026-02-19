<?php
$pendingCount = count($pendingDeposits ?? []);
?>
<div class="container mt-4">
    <h1>Administration</h1>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h5 class="card-title">Dépôts en attente</h5>
                    <p class="display-4"><?= $pendingCount ?></p>
                    <a href="/admin/deposits/pending" class="btn btn-warning">Gérer</a>
                </div>
            </div>
        </div>
        <!-- Ajoute d'autres stats ici -->
    </div>
</div>