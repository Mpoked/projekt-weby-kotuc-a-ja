<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Breadcrumbs -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Domů</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('artist') ?>">Umělci</a></li>
        <li class="breadcrumb-item active">Upravit umělce</li>
    </ol>
</nav>

<h1 class="h3 mb-4">Upravit umělce</h1>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm" style="max-width: 700px;">
    <div class="card-body">
        <form action="<?= base_url('artist/' . $artist->id . '/update') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="name" class="form-label">Název umělce <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name"
                       value="<?= esc(old('name', $artist->name)) ?>" required>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="country" class="form-label">Země původu</label>
                    <input type="text" class="form-control" id="country" name="country"
                           value="<?= esc(old('country', $artist->country ?? '')) ?>" placeholder="např. USA">
                </div>
                <div class="col-md-6">
                    <label for="formed_year" class="form-label">Rok vzniku</label>
                    <input type="number" class="form-control" id="formed_year" name="formed_year"
                           value="<?= esc(old('formed_year', $artist->formed_year ?? '')) ?>" min="1900" max="<?= date('Y') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="genres" class="form-label">Žánry</label>
                <select id="genres" name="genres[]" class="form-select" multiple>
                    <?php
                    $currentGenres = old('genres', $selected_genres ?? []);
                    foreach ($genre_options as $genre): ?>
                        <option value="<?= $genre->id ?>"
                            <?= in_array($genre->id, (array) $currentGenres) ? 'selected' : '' ?>>
                            <?= esc($genre->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Fotografie</label>
                <?php if ($artist->photo): ?>
                    <div class="mb-2">
                        <img src="<?= esc($artist->photo) ?>" alt="<?= esc($artist->name) ?>"
                             width="80" height="80" class="rounded-circle object-fit-cover">
                        <small class="text-muted ms-2">Aktuální fotografie</small>
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="photo" name="photo"
                       accept="image/jpeg,image/png,image/webp">
                <div class="form-text">Nahrajte nový soubor pouze pokud chcete změnit fotografii.</div>
            </div>

            <div class="mb-3">
                <label for="bio" class="form-label">Bio</label>
                <textarea class="form-control" id="bio" name="bio" rows="6"><?= esc(old('bio', $artist->bio)) ?></textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Uložit změny
                </button>
                <a href="<?= base_url('artist') ?>" class="btn btn-outline-secondary">Zpět</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#bio',
        base_url: 'https://cdn.jsdelivr.net/npm/tinymce@7',
        suffix: '.min',
        language: 'cs',
        plugins: 'lists link',
        toolbar: 'bold italic underline | bullist numlist | link',
        menubar: false,
        height: 250,
        license_key: 'gpl',
    });

    $('#genres').select2({
        theme: 'bootstrap-5',
        allowClear: true,
    });
</script>

<?= $this->endSection(); ?>
