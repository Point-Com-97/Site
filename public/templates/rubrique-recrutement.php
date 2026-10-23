<?php
// Template spécifique : Recrutement
// Affiche les miniatures des pages du menu triées par ordre + message de présentation

// Trier les pages par ordre
usort($pages_liees, fn($a, $b) => ($a['ordre'] ?? 0) <=> ($b['ordre'] ?? 0));
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

<!-- Message de présentation -->
<section class="recrutement-intro py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="recrutement-intro-card reveal">
                    <div class="recrutement-intro-icon">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <h2 class="h3 text-center mb-3">Rejoignez l'aventure Point Com</h2>
                    <p class="text-center text-muted mb-0">
                        Nous sommes constamment à la recherche de nouveaux talents pour renforcer nos équipes
                        et accompagner nos apprenants. Découvrez nos offres d'emploi et opportunités
                        proposées par nos entreprises partenaires.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container py-4">
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
        <h2 class="h4 mb-4 reveal">Nos offres en cours</h2>
        <div class="row g-4">
            <?php foreach ($pages_liees as $p): ?>
                <div class="col-12 col-md-6 col-lg-4 reveal reveal--fade-up">
                    <a class="card news-card h-100 text-decoration-none" href="<?= e('/' . $p['slug']) ?>">
                        <div class="news-card-image">
                            <img src="/assets/image/rubriques/recrutement.svg" alt="" loading="lazy">
                            <span class="news-category"><i class="bi bi-briefcase"></i> Offre</span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h3 class="news-card-title"><?= e($p['titre']) ?></h3>
                            <?php if (!empty($p['meta_description'])): ?>
                                <p class="news-card-excerpt"><?= e($p['meta_description']) ?></p>
                            <?php endif; ?>
                            <div class="news-card-meta mt-auto">
                                <span class="news-read-more">Voir l'offre <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
