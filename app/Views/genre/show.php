<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Domů</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('genre') ?>">Žánry</a></li>
        <li class="breadcrumb-item active"><?= esc($genre['name']) ?></li>
    </ol>
</nav>

<h1 class="h3 mb-2"><?= esc($genre['name']) ?></h1>
<?php if ($genre['description']): ?>
    <p class="text-muted mb-4"><?= esc($genre['description']) ?></p>
<?php endif; ?>

<h2 class="h5 mb-3">Umělci v tomto žánru</h2>

<?php if (empty($artists)): ?>
    <p class="text-muted">Žádní umělci v tomto žánru.</p>
<?php else: ?>
<div class="row row-cols-2 row-cols-md-4 g-3">
    <?php foreach ($artists as $artist): ?>
    <div class="col">
        <a href="<?= base_url('artist/' . $artist['id']) ?>" class="text-decoration-none text-dark">
            <div class="card h-100 shadow-sm text-center p-2">
                <?php if ($artist['photo']): ?>
                    <img src="<?= esc($artist['photo']) ?>" class="rounded-circle mx-auto mt-2 object-fit-cover"
                         width="80" height="80" alt="<?= esc($artist['name']) ?>">
                <?php else: ?>
                    <div class="rounded-circle bg-secondary text-white mx-auto mt-2 d-flex align-items-center justify-content-center" style="width:80px;height:80px;">
                        <i class="bi bi-person-fill fs-3"></i>
                    </div>
                <?php endif; ?>
                <div class="card-body p-2">
                    <div class="fw-semibold small"><?= esc($artist['name']) ?></div>
                    <?php if ($artist['country']): ?>
                        <div class="text-muted small"><?= esc($artist['country']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?= $this->endSection(); ?>
