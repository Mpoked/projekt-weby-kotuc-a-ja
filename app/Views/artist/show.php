<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Domů</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('artist') ?>">Umělci</a></li>
        <li class="breadcrumb-item active"><?= esc($artist->name) ?></li>
    </ol>
</nav>

<div class="row mb-4">
    <div class="col-md-3 text-center">
        <?php if ($artist->photo): ?>
            <img src="<?= esc($artist->photo) ?>" alt="<?= esc($artist->name) ?>"
                 class="img-fluid rounded shadow" style="max-height:250px;">
        <?php else: ?>
            <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="height:200px;">
                <i class="bi bi-person-fill fs-1"></i>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-start">
            <h1 class="h2"><?= esc($artist->name) ?></h1>
            <?php if (session()->get('role') === 'admin'): ?>
            <div class="d-flex gap-2">
                <a href="<?= base_url('artist/' . $artist->id . '/edit') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-pencil"></i> Upravit
                </a>
            </div>
            <?php endif; ?>
        </div>

        <?php if (! empty($genres)): ?>
        <div class="mb-2">
            <?php foreach ($genres as $g): ?>
                <a href="<?= base_url('genre/' . $g->id) ?>" class="badge bg-secondary text-decoration-none me-1">
                    <?= esc($g->name) ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($artist->country || $artist->formed_year): ?>
        <p class="text-muted mb-2">
            <?= $artist->country ? esc($artist->country) : '' ?>
            <?= $artist->country && $artist->formed_year ? ' · ' : '' ?>
            <?= $artist->formed_year ? 'od ' . esc($artist->formed_year) : '' ?>
        </p>
        <?php endif; ?>

        <?php if ($artist->bio): ?>
        <div class="mt-3"><?= $artist->bio ?></div>
        <?php endif; ?>
    </div>
</div>

<h2 class="h4 mb-3">Alba</h2>
<?php if (empty($albums)): ?>
    <p class="text-muted">Žádná alba.</p>
<?php else: ?>
<div class="row row-cols-2 row-cols-md-4 g-3">
    <?php foreach ($albums as $album): ?>
    <div class="col">
        <a href="<?= base_url('album/' . $album->id) ?>" class="text-decoration-none text-dark">
            <div class="card h-100 shadow-sm">
                <?php if ($album->cover_image): ?>
                    <img src="<?= esc($album->cover_image) ?>" class="card-img-top object-fit-cover" style="height:180px;" alt="<?= esc($album->title) ?>">
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height:180px;">
                        <i class="bi bi-disc fs-1 text-muted"></i>
                    </div>
                <?php endif; ?>
                <div class="card-body p-2">
                    <div class="fw-semibold small"><?= esc($album->title) ?></div>
                    <div class="text-muted small"><?= $album->release_date ? date('Y', strtotime($album->release_date)) : '' ?></div>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?= $this->endSection(); ?>
