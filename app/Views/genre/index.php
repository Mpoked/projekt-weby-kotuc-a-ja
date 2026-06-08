<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Domů</a></li>
        <li class="breadcrumb-item active">Žánry</li>
    </ol>
</nav>

<h1 class="h3 mb-4">Hudební žánry</h1>

<?php if (empty($genres)): ?>
    <p class="text-muted">Žádné žánry nenalezeny.</p>
<?php else: ?>
<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
    <?php foreach ($genres as $genre): ?>
    <div class="col">
        <a href="<?= base_url('genre/' . $genre->id) ?>" class="text-decoration-none text-dark">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title"><?= esc($genre->name) ?></h5>
                    <?php if ($genre->description): ?>
                        <p class="card-text text-muted small">
                            <?= esc(mb_substr($genre->description, 0, 80)) ?><?= mb_strlen($genre->description) > 80 ? '…' : '' ?>
                        </p>
                    <?php endif; ?>
                    <span class="badge bg-primary"><?= (int)$genre->artist_count ?> umělců</span>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?= $this->endSection(); ?>
