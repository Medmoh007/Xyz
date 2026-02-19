<?php
// Messages flash globaux
if (isset($_SESSION['flash_success'])): ?>
    <div class="alert alert-success fade-in">
        <i class="fas fa-check-circle"></i>
        <?= htmlspecialchars($_SESSION['flash_success']) ?>
        <button type="button" class="btn-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert alert-error fade-in">
        <i class="fas fa-exclamation-circle"></i>
        <?= htmlspecialchars($_SESSION['flash_error']) ?>
        <button type="button" class="btn-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['flash_errors']) && is_array($_SESSION['flash_errors'])): ?>
    <div class="alert alert-error fade-in">
        <i class="fas fa-exclamation-triangle"></i>
        <ul style="margin:0; padding-left:20px;">
            <?php foreach ($_SESSION['flash_errors'] as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
    <?php unset($_SESSION['flash_errors']); ?>
<?php endif; ?>

<style>
.alert .btn-close {
    margin-left: auto;
    background: none;
    border: none;
    color: inherit;
    font-size: 1.2rem;
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.2s;
}
.alert .btn-close:hover {
    opacity: 1;
}
</style>