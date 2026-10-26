<?php
// Template spécifique : Nos formations
// Affiche une rubrique par sous-menu avec la liste des formations liées

$sous_menus = $conf['sous_menus'] ?? [];
$icones_sous_menus = [
    'niveau-3' => 'bi-award',
    'niveau-4' => 'bi-award-fill',
    'niveau-5' => 'bi-trophy',
    'atelier'  => 'bi-tools',
];
?>
<section class="page-titre text-white py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="<?= !empty($conf['image']) ? 'col-12 col-lg-7' : 'col-12 text-center' ?>">
                <h1 class="reveal"><i class="bi <?= e($icone) ?>"></i> <?= e($current_page['titre']) ?></h1>
                <?php if (!empty($conf['intro'])): ?>
                    <p class="lead reveal"><?= e($conf['intro']) ?></p>
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

<div class="container py-5">
    <?php render_blocs($all_blocs); ?>

    <?php if (empty($all_blocs) && empty($pages_liees)): ?>
        <!-- Message menu vide -->
        <div class="row justify-content-center py-5">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="empty-menu-message-container">
                    <div class="empty-menu-message text-center reveal reveal--fade-up">
                        <div class="empty-menu-icon mb-4">
                            <div class="icon-wrapper">
                                <i class="bi <?= e($empty_message_icon) ?>"></i>
                            </div>
                        </div>
                        <h2 class="empty-menu-title h3 mb-3"><?= e($empty_message_title) ?></h2>
                        <p class="empty-menu-text mb-4"><?= e($empty_message_text) ?></p>
                        <?php if ($empty_message_cta && $empty_message_cta_url): ?>
                            <a href="<?= e($empty_message_cta_url) ?>" class="btn btn-primary btn-lg empty-menu-cta reveal reveal-delay-1">
                                <i class="bi bi-arrow-right"></i> <?= e($empty_message_cta) ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($pages_liees)): ?>
        <?php foreach ($sous_menus as $cle_sous_menu => $label): ?>
            <?php
            $pages_sous_menu = array_values(array_filter($pages_liees, fn($p) => ($p['sous_menu'] ?? '') === $cle_sous_menu));
            if (empty($pages_sous_menu)) continue;
            $icone_sm = $icones_sous_menus[$cle_sous_menu] ?? 'bi-book';
            ?>
            <section class="formation-rubrique mb-5 reveal">
                <div class="formation-rubrique-header d-flex align-items-center gap-3 mb-4">
                    <div class="formation-rubrique-icon">
                        <i class="bi ico <?= e($icone_sm) ?>"></i>
                    </div>
                    <div>
                        <h2 class="h3 mb-1"><?= e($label) ?></h2>
                        <p class="text-muted mb-0"><?= count($pages_sous_menu) ?> formation<?= count($pages_sous_menu) > 1 ? 's' : '' ?></p>
                    </div>
                </div>
                <div class="row g-3">
                    <?php foreach ($pages_sous_menu as $p): ?>
                        <div class="col-12 col-md-6 col-lg-4 reveal reveal--fade-up">
                            <a class="card formation-card h-100 text-decoration-none" href="/<?= e($p['slug']) ?>">
                                <div class="card-body d-flex flex-column">
                                    <h3 class="h5 formation-card-title"><?= e($p['titre']) ?></h3>
                                    <?php if (!empty($p['meta_description'])): ?>
                                        <p class="formation-card-text"><?= e($p['meta_description']) ?></p>
                                    <?php endif; ?>
                                    <div class="formation-card-footer mt-auto">
                                        <span class="formation-link">Voir la formation <i class="bi bi-arrow-right"></i></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>

        <!-- Formations sans sous-menu (au cas où) -->
        <?php
        $pages_sans_sm = array_values(array_filter($pages_liees, fn($p) => empty($p['sous_menu'])));
        if (!empty($pages_sans_sm)):
        ?>
            <section class="formation-rubrique mb-5 reveal">
                <div class="formation-rubrique-header d-flex align-items-center gap-3 mb-4">
                    <div class="formation-rubrique-icon">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <div>
                        <h2 class="h3 mb-1">Autres formations</h2>
                        <p class="text-muted mb-0"><?= count($pages_sans_sm) ?> formation<?= count($pages_sans_sm) > 1 ? 's' : '' ?></p>
                    </div>
                </div>
                <div class="row g-3">
                    <?php foreach ($pages_sans_sm as $p): ?>
                        <div class="col-12 col-md-6 col-lg-4 reveal reveal--fade-up">
                            <a class="card formation-card h-100 text-decoration-none" href="/<?= e($p['slug']) ?>">
                                <div class="card-body d-flex flex-column">
                                    <h3 class="h5 formation-card-title"><?= e($p['titre']) ?></h3>
                                    <?php if (!empty($p['meta_description'])): ?>
                                        <p class="formation-card-text"><?= e($p['meta_description']) ?></p>
                                    <?php endif; ?>
                                    <div class="formation-card-footer mt-auto">
                                        <span class="formation-link">Voir la formation <i class="bi bi-arrow-right"></i></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endif; ?>
</div>
