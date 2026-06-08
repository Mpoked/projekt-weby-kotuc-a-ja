<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm mt-4">
            <div class="card-header text-center fw-bold">Registrace</div>
            <div class="card-body">
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                                <li><?= esc($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= base_url('register') ?>">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="username" class="form-label">Uživatelské jméno <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username"
                               value="<?= esc(old('username')) ?>" required minlength="3" maxlength="50">
                        <div class="form-text">Minimálně 3 znaky.</div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email"
                               value="<?= esc(old('email')) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Heslo <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password"
                                   required minlength="8">
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        <div class="form-text">Minimálně 8 znaků.</div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirm" class="form-label">Heslo znovu <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm"
                                   required minlength="8">
                            <button type="button" class="btn btn-outline-secondary" id="toggleConfirm" tabindex="-1">
                                <i class="bi bi-eye" id="eyeIconConfirm"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Zaregistrovat se</button>
                    </div>
                </form>

                <hr>
                <p class="text-center mb-0">
                    Máte účet? <a href="<?= base_url('login') ?>">Přihlaste se</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleVisibility(inputId, iconId) {
        const inp  = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            inp.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    document.getElementById('togglePassword').addEventListener('click', () => toggleVisibility('password', 'eyeIcon'));
    document.getElementById('toggleConfirm').addEventListener('click', () => toggleVisibility('password_confirm', 'eyeIconConfirm'));
</script>

<?= $this->endSection(); ?>
