<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) . ' – Music Archive' : 'Music Archive' ?></title>
    <?= $this->include("layout/css") ?>
</head>
<body class="bg-light">
    <?= $this->include("layout/navbar") ?>

    <div class="container mt-3 mb-5">
        <?php $alert = session()->getFlashdata('alert'); ?>
        <?php if ($alert): ?>
        <div class="alert alert-<?= esc($alert['type']) ?> alert-dismissible fade show" role="alert">
            <?= esc($alert['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?= $this->renderSection("content") ?>
    </div>
</body>
</html>