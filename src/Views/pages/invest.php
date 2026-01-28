<h2>Nouvel investissement</h2>

<form method="post">
    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
    <input type="number" name="amount" placeholder="Montant" required>
    <select name="plan">
        <option value="basic">Basic</option>
    </select>
    <button>Investir</button>
</form>
