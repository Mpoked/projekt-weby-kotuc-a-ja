<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm mt-4">
            <div class="card-header text-center fw-bold">Přihlášení</div>
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

                <form method="post" action="<?= base_url('login') ?>">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="login" class="form-label">Email nebo uživatelské jméno</label>
                        <input type="text" class="form-control" id="login" name="login"
                               value="<?= esc(old('login')) ?>" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Heslo</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Přihlásit se</button>
                    </div>
                </form>

                <hr>
                <p class="text-center mb-0">
                    Nemáte účet? <a href="<?= base_url('register') ?>">Zaregistrujte se</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const inp  = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            inp.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });
</script>

<?= $this->endSection(); ?>
