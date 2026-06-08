<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Domů</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('album') ?>">Alba</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('album/' . $album->id) ?>"><?= esc($album->title) ?></a></li>
        <li class="breadcrumb-item active">Přidat skladbu</li>
    </ol>
</nav>

<h1 class="h3 mb-4">Přidat skladbu</h1>

<div class="card shadow-sm" style="max-width: 600px;">
    <div class="card-body">
        <form action="<?= base_url('album/' . $album->id . '/track/store') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="title" class="form-label">Název skladby <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title"
                       value="<?= esc(old('title')) ?>" required>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="track_number" class="form-label">Číslo stopy</label>
                    <input type="number" class="form-control" id="track_number" name="track_number"
                           value="<?= esc(old('track_number')) ?>" min="1">
                </div>
                <div class="col-md-6">
                    <label for="duration" class="form-label">Délka (v sekundách)</label>
                    <input type="number" class="form-control" id="duration" name="duration"
                           value="<?= esc(old('duration')) ?>" min="0" placeholder="např. 213">
                    <div class="form-text">Např. 3:33 = 213 sekund</div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Uložit
                </button>
                <a href="<?= base_url('album/' . $album->id) ?>" class="btn btn-outline-secondary">Zpět</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>
