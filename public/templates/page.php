<?php
require_once __DIR__ . '/blocs.php';

// Si c'est la page d'une rubrique (formation, alternance...), on liste les pages du menu en dessous
$pages_liees = $cle_menu ? ($page_group[$cle_menu] ?? []) : [];
$icone = $cle_menu ? list_menus()[$cle_menu]['icone'] : null;
?>
<section class="page-titre text-center text-white py-5">
    <div class="container">
        <h1 class="reveal"><?php if ($icone): ?><i class="bi <?= e($icone) ?>"></i> <?php endif; ?><?= e($current_page['titre']) ?></h1>
        <?php if (!empty($current_page['meta_description'])): ?>
            <p class="lead reveal"><?= e($current_page['meta_description']) ?></p>
        <?php endif; ?>
    </div>
</section>

<div class="container py-4">
    <?php render_blocs($all_blocs); ?>

    <?php if (empty($all_blocs) && !$est_contact && empty($pages_liees)): ?>
        <p class="text-muted text-center">Cette page ne contient pas encore de contenu.</p>
    <?php endif; ?>

    <?php if ($est_contact): require __DIR__ . '/contact-form.php'; endif; ?>

    <?php if (!empty($pages_liees)): ?>
        <h2 class="h4 mt-4 mb-3">Dans cette rubrique</h2>
        <div class="row g-3">
            <?php foreach ($pages_liees as $p): ?>
                <div class="col-12 col-md-6 col-lg-4 reveal">
                    <a class="card card-rubrique h-100 text-decoration-none" href="/<?= e($p['slug']) ?>">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <span><?= e($p['titre']) ?></span><i class="bi bi-arrow-right"></i>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
