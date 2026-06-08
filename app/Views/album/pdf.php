<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 22px; margin-bottom: 4px; }
        h2 { font-size: 15px; margin-top: 20px; border-bottom: 1px solid #ccc; padding-bottom: 4px; }
        .meta { color: #666; font-size: 11px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { background: #f0f0f0; text-align: left; padding: 5px 8px; font-size: 11px; }
        td { padding: 4px 8px; border-bottom: 1px solid #eee; }
        .rating { background: #f0ad4e; padding: 2px 6px; border-radius: 4px; font-weight: bold; }
        .review { border: 1px solid #ddd; padding: 8px; margin-bottom: 8px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1><?= htmlspecialchars($album['title']) ?></h1>
    <div class="meta">
        <?= htmlspecialchars($album['artist_name']) ?>
        <?= $album['release_date'] ? ' · ' . date('Y', strtotime($album['release_date'])) : '' ?>
        <?= $album['label'] ? ' · ' . htmlspecialchars($album['label']) : '' ?>
        <?php if ($album['avg_rating']): ?>
            · <span class="rating">★ <?= number_format($album['avg_rating'], 1) ?>/10</span>
        <?php endif; ?>
    </div>

    <?php if ($album['description']): ?>
    <p><?= strip_tags($album['description']) ?></p>
    <?php endif; ?>

    <?php if (! empty($album['tracks'])): ?>
    <h2>Tracklist</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Název</th>
                <th>Délka</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($album['tracks'] as $track): ?>
            <tr>
                <td><?= htmlspecialchars($track['track_number'] ?? '–') ?></td>
                <td><?= htmlspecialchars($track['title']) ?></td>
                <td><?= $track['duration'] ? gmdate('i:s', $track['duration']) : '–' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <?php if (! empty($album['reviews'])): ?>
    <h2>Recenze</h2>
    <?php foreach ($album['reviews'] as $review): ?>
    <div class="review">
        <strong><?= htmlspecialchars($review['username']) ?></strong>
        · <span class="rating">★ <?= htmlspecialchars($review['rating']) ?>/10</span>
        · <small><?= date('d.m.Y', strtotime($review['created_at'])) ?></small>
        <p><?= htmlspecialchars($review['body']) ?></p>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

    <p style="color:#999;font-size:10px;margin-top:20px;">Vygenerováno: <?= date('d.m.Y H:i') ?> · Music Archive</p>
</body>
</html>
