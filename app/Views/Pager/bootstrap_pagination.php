<?php $pager->setSurroundCount(2) ?>

<nav aria-label="Stránkování">
    <ul class="pagination justify-content-center">

        <?php if ($pager->hasPreviousPage()): ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getPreviousPageURI() ?>">&laquo;</a>
            </li>
        <?php else: ?>
            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
        <?php endif; ?>

        <?php foreach ($pager->links() as $link): ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
            </li>
        <?php endforeach; ?>

        <?php if ($pager->hasNextPage()): ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getNextPageURI() ?>">&raquo;</a>
            </li>
        <?php else: ?>
            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
        <?php endif; ?>

    </ul>
</nav>
