<?php
require_once __DIR__ . '/blocs.php';

// $cle_menu est défini dans index.php : clé du menu si la page est une page de rubrique (formation, alternance...)
$conf = $cle_menu ? list_menus()[$cle_menu] : null;
$icone = $conf['icone'] ?? null;
$pages_liees = $cle_menu ? ($page_group[$cle_menu] ?? []) : [];

// Pages de la rubrique : regroupées par sous-menu si le menu en a, sinon une seule liste
$groupes = [];
if (!empty($pages_liees)) {
    $groupes = !empty($conf['sous_menus'])
        ? group_by_sous_menu($pages_liees, $conf['sous_menus'])
        : ['' => $pages_liees];
}
?>
<section class="page-titre text-white py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="<?= !empty($conf['image']) ? 'col-12 col-lg-7' : 'col-12 text-center' ?>">
                <h1 class="reveal"><?php if ($icone): ?><i class="bi <?= e($icone) ?>"></i> <?php endif; ?><?= e($current_page['titre']) ?></h1>
                <?php if (!empty($conf['intro'])): ?>
                    <p class="lead reveal"><?= e($conf['intro']) ?></p>
                <?php elseif (!empty($current_page['meta_description'])): ?>
                    <p class="lead reveal"><?= e($current_page['meta_description']) ?></p>
                <?php endif; ?>
            </div>
            <?php if (!empty($conf['image'])): ?>
                <div class="col-12 col-lg-5 reveal">
                    <img src="<?= e($conf['image']) ?>" alt="" class="img-fluid rounded" loading="lazy">
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<div class="container py-4">
    <?php render_blocs($all_blocs); ?>

    <?php if (empty($all_blocs) && !$est_contact && empty($pages_liees) && empty($conf)): ?>
        <p class="text-muted text-center">Cette page ne contient pas encore de contenu.</p>
    <?php endif; ?>

    <?php if ($est_contact) {
        require __DIR__ . '/contact-form.php';
    } ?>

    <?php foreach ($groupes as $label => $liste): ?>
        <?php if ($label === ''): ?>
            <h2 class="h4 mt-4 mb-3">Dans cette rubrique</h2>
        <?php else: ?>
            <h2 class="h4 mt-4 mb-3"><?= e($label) ?></h2>
        <?php endif; ?>
        <div class="row g-3">
            <?php foreach ($liste as $p): ?>
                <div class="col-12 col-md-6 col-lg-4 reveal">
                    <a class="card card-rubrique h-100 text-decoration-none" href="/<?= e($p['slug']) ?>">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <span><?= e($p['titre']) ?></span><i class="bi bi-arrow-right"></i>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>
