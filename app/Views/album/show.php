<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Domů</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('album') ?>">Alba</a></li>
        <li class="breadcrumb-item active"><?= esc($album['title']) ?></li>
    </ol>
</nav>

<div class="row mb-4">
    <div class="col-md-3 text-center mb-3">
        <?php if ($album['cover_image']): ?>
            <img src="<?= esc($album['cover_image']) ?>" class="img-fluid rounded shadow" style="max-height:280px;" alt="<?= esc($album['title']) ?>">
        <?php else: ?>
            <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="height:250px;">
                <i class="bi bi-disc fs-1 text-muted"></i>
            </div>
        <?php endif; ?>
        <div class="mt-2">
            <a href="<?= base_url('album/' . $album['id'] . '/pdf') ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-file-pdf"></i> Stáhnout PDF
            </a>
        </div>
    </div>

    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="h2 mb-1"><?= esc($album['title']) ?></h1>
                <p class="text-muted mb-1">
                    <a href="<?= base_url('artist/' . $album['artist_id']) ?>" class="text-muted">
                        <?= esc($album['artist_name']) ?>
                    </a>
                    <?= $album['release_date'] ? ' · ' . date('Y', strtotime($album['release_date'])) : '' ?>
                    <?= $album['label'] ? ' · ' . esc($album['label']) : '' ?>
                </p>
                <?php if ($album['avg_rating']): ?>
                <p class="mb-1">
                    <span class="badge bg-warning text-dark fs-6">
                        ★ <?= number_format($album['avg_rating'], 1) ?>/10
                    </span>
                    <small class="text-muted ms-1"><?= $album['review_count'] ?> recenzí</small>
                </p>
                <?php endif; ?>
            </div>
            <?php if (session()->get('role') === 'admin'): ?>
            <div class="d-flex gap-2">
                <a href="<?= base_url('album/' . $album['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-pencil"></i> Upravit
                </a>
                <button type="button" class="btn btn-sm btn-outline-danger"
                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash"></i> Smazat
                </button>
            </div>
            <?php endif; ?>
        </div>

        <?php if ($album['description']): ?>
        <div class="mt-3"><?= $album['description'] ?></div>
        <?php endif; ?>
    </div>
</div>

<!-- Tracklist -->
<?php if (! empty($album['tracks'])): ?>
<h2 class="h5 mb-2">Skladby</h2>
<div class="table-responsive mb-4">
    <table class="table table-sm table-hover">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Název</th>
                <th>Délka</th>
                <?php if (session()->get('role') === 'admin'): ?>
                <th>Akce</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($album['tracks'] as $track): ?>
            <tr>
                <td class="text-muted"><?= esc($track['track_number'] ?? '–') ?></td>
                <td><?= esc($track['title']) ?></td>
                <td class="text-muted">
                    <?php if ($track['duration']): ?>
                        <?= gmdate('i:s', $track['duration']) ?>
                    <?php else: ?>
                        –
                    <?php endif; ?>
                </td>
                <?php if (session()->get('role') === 'admin'): ?>
                <td>
                    <a href="<?= base_url('album/' . $album['id'] . '/track/' . $track['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary py-0">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger py-0"
                            data-bs-toggle="modal" data-bs-target="#deleteTrackModal"
                            data-id="<?= $track['id'] ?>" data-name="<?= esc($track['title']) ?>">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php if (session()->get('role') === 'admin'): ?>
<a href="<?= base_url('album/' . $album['id'] . '/track/create') ?>" class="btn btn-sm btn-outline-primary mb-4">
    <i class="bi bi-plus"></i> Přidat skladbu
</a>
<?php endif; ?>

<!-- Recenze -->
<h2 class="h5 mb-3">Recenze</h2>
<?php if (empty($album['reviews'])): ?>
    <p class="text-muted">Zatím žádné recenze.</p>
<?php else: ?>
    <?php foreach ($album['reviews'] as $review): ?>
    <div class="card mb-2">
        <div class="card-body py-2">
            <div class="d-flex justify-content-between align-items-center">
                <strong><?= esc($review['username']) ?></strong>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark">★ <?= esc($review['rating']) ?>/10</span>
                    <?php if (session()->get('role') === 'admin'): ?>
                    <form method="post" action="<?= base_url('album/' . $album['id'] . '/review/' . $review['id'] . '/delete') ?>" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger py-0" onclick="return confirm('Smazat recenzi?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <p class="mb-0 mt-1"><?= esc($review['body']) ?></p>
            <small class="text-muted"><?= date('d.m.Y', strtotime($review['created_at'])) ?></small>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Formulář pro přidání recenze -->
<?php if (session()->get('logged_in') && ! $userReviewExists): ?>
<div class="card mt-3">
    <div class="card-header">Přidat recenzi</div>
    <div class="card-body">
        <form method="post" action="<?= base_url('album/' . $album['id'] . '/review/store') ?>">
            <?= csrf_field() ?>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="rating" class="form-label">Hodnocení (1–10) <span class="text-danger">*</span></label>
                    <input type="number" name="rating" id="rating" class="form-control" min="1" max="10" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="body" class="form-label">Text recenze <span class="text-danger">*</span></label>
                <textarea name="body" id="body" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Odeslat recenzi</button>
        </form>
    </div>
</div>
<?php elseif (! session()->get('logged_in')): ?>
<p class="text-muted mt-3"><a href="<?= base_url('login') ?>">Přihlaste se</a> pro přidání recenze.</p>
<?php endif; ?>

<!-- Delete modaly -->
<?php if (session()->get('role') === 'admin'): ?>
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Smazat album</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">Opravdu chcete smazat toto album?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušit</button>
                <form method="post" action="<?= base_url('album/' . $album['id'] . '/delete') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Smazat</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteTrackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Smazat skladbu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">Smazat skladbu <strong id="deleteTrackName"></strong>?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušit</button>
                <form id="deleteTrackForm" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Smazat</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('deleteTrackModal').addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        document.getElementById('deleteTrackName').textContent = btn.getAttribute('data-name');
        document.getElementById('deleteTrackForm').action =
            '<?= base_url('album/' . $album['id'] . '/track/') ?>' + btn.getAttribute('data-id') + '/delete';
    });
</script>
<?php endif; ?>

<?= $this->endSection(); ?>
