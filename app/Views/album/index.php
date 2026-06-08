<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Domů</a></li>
        <li class="breadcrumb-item active">Alba</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Alba</h1>
    <?php if (session()->get('role') === 'admin'): ?>
    <a href="<?= base_url('album/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Přidat album
    </a>
    <?php endif; ?>
</div>

<!-- Filtr -->
<form method="get" action="<?= base_url('album') ?>" class="row g-2 mb-4">
    <div class="col-md-4">
        <select name="genre_id" class="form-select">
            <option value="">— Všechny žánry —</option>
            <?php foreach ($genres as $g): ?>
                <option value="<?= $g['id'] ?>" <?= (string)($filters['genre_id'] ?? '') === (string)$g['id'] ? 'selected' : '' ?>>
                    <?= esc($g['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <select name="year" class="form-select">
            <option value="">— Všechny roky —</option>
            <?php foreach ($years as $y): ?>
                <option value="<?= $y ?>" <?= (string)($filters['year'] ?? '') === (string)$y ? 'selected' : '' ?>>
                    <?= esc($y) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-primary">Filtrovat</button>
        <a href="<?= base_url('album') ?>" class="btn btn-outline-secondary">Zrušit</a>
    </div>
</form>

<?php if (empty($albums)): ?>
    <p class="text-muted">Žádná alba nenalezena.</p>
<?php else: ?>
<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
    <?php foreach ($albums as $album): ?>
    <div class="col">
        <div class="card h-100 shadow-sm">
            <a href="<?= base_url('album/' . $album['id']) ?>">
                <?php if ($album['cover_image']): ?>
                    <img src="<?= esc($album['cover_image']) ?>" class="card-img-top object-fit-cover"
                         style="height:200px;" alt="<?= esc($album['title']) ?>">
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height:200px;">
                        <i class="bi bi-disc fs-1 text-muted"></i>
                    </div>
                <?php endif; ?>
            </a>
            <div class="card-body">
                <h6 class="card-title mb-1">
                    <a href="<?= base_url('album/' . $album['id']) ?>" class="text-decoration-none text-dark">
                        <?= esc($album['title']) ?>
                    </a>
                </h6>
                <p class="card-text text-muted small mb-0">
                    <a href="<?= base_url('artist/' . $album['artist_id']) ?>" class="text-muted text-decoration-none">
                        <?= esc($album['artist_name']) ?>
                    </a>
                </p>
                <?php if ($album['release_date']): ?>
                    <p class="card-text text-muted small"><?= date('Y', strtotime($album['release_date'])) ?></p>
                <?php endif; ?>
            </div>
            <?php if (session()->get('role') === 'admin'): ?>
            <div class="card-footer bg-transparent d-flex gap-1 p-2">
                <a href="<?= base_url('album/' . $album['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary flex-fill">
                    <i class="bi bi-pencil"></i>
                </a>
                <button type="button" class="btn btn-sm btn-outline-danger flex-fill"
                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                        data-id="<?= $album['id'] ?>" data-name="<?= esc($album['title']) ?>">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="mt-4 d-flex justify-content-center">
    <?= $pager->links('default', 'bootstrap_pagination') ?>
</div>
<?php endif; ?>

<!-- Modální okno pro smazání -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Smazat album</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Opravdu chcete smazat album <strong id="deleteAlbumName"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušit</button>
                <form id="deleteForm" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Smazat</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('deleteModal').addEventListener('show.bs.modal', function(e) {
        const btn  = e.relatedTarget;
        document.getElementById('deleteAlbumName').textContent = btn.getAttribute('data-name');
        document.getElementById('deleteForm').action = '<?= base_url('album/') ?>' + btn.getAttribute('data-id') + '/delete';
    });
</script>

<?= $this->endSection(); ?>
