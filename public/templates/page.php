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

// Message personnalisé pour menu vide
$empty_message_title = $conf['empty_title'] ?? 'Contenu en préparation';
$empty_message_text = $conf['empty_text'] ?? 'Cette page est en cours de mise à jour. Revenez bientôt pour découvrir son contenu.';
$empty_message_icon = $conf['empty_icon'] ?? 'bi-info-circle-fill';
$empty_message_cta = $conf['empty_cta'] ?? null;
$empty_message_cta_url = $conf['empty_cta_url'] ?? null;

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
        <div class="row justify-content-center py-5">
            <div class="col-12 col-md-10 col-lg-8">
                <!-- NOUVEAU MESSAGE ANIMÉ POUR MENU VIDE -->
                <div class="empty-menu-message-container">
                    <div class="empty-menu-message text-center reveal reveal--fade-up">
                        <!-- Icône animée -->
                        <div class="empty-menu-icon mb-4">
                            <div class="icon-wrapper">
                                <i class="bi <?= e($empty_message_icon) ?>"></i>
                            </div>
                        </div>
                        
                        <!-- Titre -->
                        <h2 class="empty-menu-title h3 mb-3"><?= e($empty_message_title) ?></h2>
                        
                        <!-- Texte -->
                        <p class="empty-menu-text mb-4"><?= e($empty_message_text) ?></p>
                        
                        <!-- Bouton CTA (optionnel) -->
                        <?php if ($empty_message_cta && $empty_message_cta_url): ?>
                            <a href="<?= e($empty_message_cta_url) ?>" 
                               class="btn btn-primary btn-lg empty-menu-cta reveal reveal-delay-1">
                                <i class="bi bi-arrow-right"></i> <?= e($empty_message_cta) ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
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
                <div class="col-12 col-md-6 col-lg-4 reveal reveal--fade-up">
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
