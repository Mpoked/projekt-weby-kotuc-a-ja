<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Domů</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('album') ?>">Alba</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('album/' . $album['id']) ?>"><?= esc($album['title']) ?></a></li>
        <li class="breadcrumb-item active">Upravit</li>
    </ol>
</nav>

<h1 class="h3 mb-4">Upravit album</h1>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm" style="max-width: 750px;">
    <div class="card-body">
        <form action="<?= base_url('album/' . $album['id'] . '/update') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="artist_id" class="form-label">Umělec <span class="text-danger">*</span></label>
                <select name="artist_id" id="artist_id" class="form-select" required>
                    <option value="" disabled>— Vyberte umělce —</option>
                    <?php foreach ($artist_options as $a): ?>
                        <option value="<?= $a['id'] ?>"
                            <?= old('artist_id', $album['artist_id']) == $a['id'] ? 'selected' : '' ?>>
                            <?= esc($a['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Název alba <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title"
                       value="<?= esc(old('title', $album['title'])) ?>" required>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="release_date" class="form-label">Datum vydání</label>
                    <input type="date" class="form-control" id="release_date" name="release_date"
                           value="<?= esc(old('release_date', $album['release_date'] ?? '')) ?>">
                </div>
                <div class="col-md-6">
                    <label for="label" class="form-label">Vydavatelství</label>
                    <input type="text" class="form-control" id="label" name="label"
                           value="<?= esc(old('label', $album['label'] ?? '')) ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="cover_image" class="form-label">Obálka alba</label>
                <?php if ($album['cover_image']): ?>
                    <div class="mb-2">
                        <img src="<?= esc($album['cover_image']) ?>" alt="<?= esc($album['title']) ?>"
                             height="80" class="rounded">
                        <small class="text-muted ms-2">Aktuální obálka</small>
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="cover_image" name="cover_image"
                       accept="image/jpeg,image/png,image/webp">
                <div class="form-text">Nahrajte nový soubor pouze pokud chcete změnit obálku.</div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Popis</label>
                <textarea class="form-control" id="description" name="description" rows="6"><?= esc(old('description', $album['description'] ?? '')) ?></textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Uložit změny
                </button>
                <a href="<?= base_url('album/' . $album['id']) ?>" class="btn btn-outline-secondary">Zpět</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#description',
        language: 'cs',
        plugins: 'lists link',
        toolbar: 'bold italic underline | bullist numlist | link',
        menubar: false,
        height: 250,
    });
</script>

<?= $this->endSection(); ?>
