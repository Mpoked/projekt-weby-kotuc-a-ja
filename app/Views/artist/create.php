<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Breadcrumbs -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Domů</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('artist') ?>">Umělci</a></li>
        <li class="breadcrumb-item active">Přidat umělce</li>
    </ol>
</nav>

<h1 class="h3 mb-4">Přidat umělce</h1>

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
        <form action="<?= base_url('artist/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="name" class="form-label">Název umělce <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name"
                       value="<?= esc(old('name')) ?>" required>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="country" class="form-label">Země původu</label>
                    <input type="text" class="form-control" id="country" name="country"
                           value="<?= esc(old('country')) ?>" placeholder="např. USA">
                </div>
                <div class="col-md-6">
                    <label for="formed_year" class="form-label">Rok vzniku</label>
                    <input type="number" class="form-control" id="formed_year" name="formed_year"
                           value="<?= esc(old('formed_year')) ?>" min="1900" max="<?= date('Y') ?>" placeholder="např. 1990">
                </div>
            </div>

            <div class="mb-3">
                <label for="genres" class="form-label">Žánry</label>
                <select id="genres" name="genres[]" class="form-select" multiple>
                    <?php foreach ($genre_options as $genre): ?>
                        <option value="<?= $genre['id'] ?>"
                            <?= in_array($genre['id'], (array) old('genres', [])) ? 'selected' : '' ?>>
                            <?= esc($genre['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">Vyberte jeden nebo více žánrů (nepovinné).</div>
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Fotografie</label>
                <input type="file" class="form-control" id="photo" name="photo"
                       accept="image/jpeg,image/png,image/webp">
                <div class="form-text">Povolené formáty: JPG, PNG, WEBP. Max. 2 MB.</div>
            </div>

            <div class="mb-3">
                <label for="bio" class="form-label">Bio</label>
                <textarea class="form-control" id="bio" name="bio" rows="6"><?= esc(old('bio')) ?></textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Uložit
                </button>
                <a href="<?= base_url('artist') ?>" class="btn btn-outline-secondary">Zpět</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#bio',
        language: 'cs',
        plugins: 'lists link',
        toolbar: 'bold italic underline | bullist numlist | link',
        menubar: false,
        height: 250,
    });

    $('#genres').select2({
        theme: 'bootstrap-5',
        placeholder: 'Vyberte žánry...',
        allowClear: true,
    });
</script>

<?= $this->endSection(); ?>