<h2>Demande de retrait</h2>

<form method="post">
    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
    <input type="number" name="amount" placeholder="Montant" required>
    <button>Envoyer</button>
</form>
