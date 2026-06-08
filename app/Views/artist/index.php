<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Breadcrumbs -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Domů</a></li>
        <li class="breadcrumb-item active">Umělci</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Umělci</h1>
    <a href="<?= base_url('artist/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Přidat umělce
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Foto</th>
                <th>Název</th>
                <th>Bio (náhled)</th>
                <th>Přidáno</th>
                <th>Akce</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($artist_list)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">Žádní umělci nenalezeni.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($artist_list as $artist): ?>
                <tr>
                    <td><?= esc($artist->id) ?></td>
                    <td>
                        <?php if ($artist->photo): ?>
                            <img src="<?= esc($artist->photo) ?>" alt="<?= esc($artist->name) ?>"
                                 width="48" height="48" class="rounded-circle object-fit-cover">
                        <?php else: ?>
                            <span class="text-muted"><i class="bi bi-person-circle fs-3"></i></span>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($artist->name) ?></td>
                    <td class="text-muted small">
                        <?= esc(mb_substr(strip_tags($artist->bio ?? ''), 0, 80)) ?><?= strlen($artist->bio ?? '') > 80 ? '…' : '' ?>
                    </td>
                    <td><?= esc(date('d.m.Y', strtotime($artist->created_at))) ?></td>
                    <td>
                        <a href="<?= base_url('artist/' . $artist->id . '/edit') ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal"
                                data-id="<?= $artist->id ?>"
                                data-name="<?= esc($artist->name) ?>">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modální okno pro smazání -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Smazat umělce</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Opravdu chcete smazat umělce <strong id="deleteArtistName"></strong>?
                Tato akce je nevratná.
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
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (e) {
        const btn  = e.relatedTarget;
        const id   = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name');
        document.getElementById('deleteArtistName').textContent = name;
        document.getElementById('deleteForm').action = `<?= base_url('artist/') ?>${id}/delete`;
    });
</script>

<?= $this->endSection(); ?>
