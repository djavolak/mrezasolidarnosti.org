<?php $this->layout('layout::standard', ['pageTitle' => 'Prijava']) ?>

<?php if ($data['sent']): ?>
    <p>Ako je email registrovan, poslali smo vam link za prijavu. Proverite inbox.</p>
<?php else: ?>
    <?php if (!empty($error)): ?><p class="error"><?= $this->e($error) ?></p><?php endif ?>
    <form method="post" action="/donor/login">
        <label>Email
            <input type="email" name="email" value="<?= $this->e($data['email'] ?? '') ?>" required>
        </label>
        <button type="submit">Pošalji link za prijavu</button>
    </form>
<?php endif ?>