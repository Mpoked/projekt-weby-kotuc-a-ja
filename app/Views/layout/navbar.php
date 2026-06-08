<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= base_url('/') ?>">
            <i class="bi bi-music-note-beamed"></i> Music Archive
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('album') ?>">
                        <i class="bi bi-disc"></i> Alba
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('artist') ?>">
                        <i class="bi bi-person-badge"></i> Umělci
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('genre') ?>">
                        <i class="bi bi-tags"></i> Žánry
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (session()->get('logged_in')): ?>
                    <li class="nav-item">
                        <span class="nav-link text-light">
                            <i class="bi bi-person-circle"></i>
                            <?= esc(session()->get('username')) ?>
                            <?php if (session()->get('role') === 'admin'): ?>
                                <span class="badge bg-warning text-dark ms-1">admin</span>
                            <?php endif; ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <form action="<?= base_url('logout') ?>" method="post" class="d-inline m-0">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-light">
                                <i class="bi bi-box-arrow-right"></i> Odhlásit
                            </button>
                        </form>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('login') ?>">
                            <i class="bi bi-box-arrow-in-right"></i> Přihlásit se
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('register') ?>">Registrace</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
