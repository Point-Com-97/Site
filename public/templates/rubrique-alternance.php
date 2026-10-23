<?php
// Template spécifique : Alternance
// Ajoute des textes et une mise en page améliorée
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

<!-- Section présentation alternance -->
<section class="alternance-section py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-12 col-lg-6 reveal">
                <div class="alternance-content">
                    <h2 class="h3 mb-4">L'alternance, un tremplin vers l'emploi</h2>
                    <p class="lead">L'alternance permet de combiner formation théorique en centre de formation et expérience pratique en entreprise. C'est la voie royale pour acquérir des compétences concrètes tout en préparant un diplôme reconnu.</p>
                    <p>Chez Point Com, nous accompagnons chaque apprenti dans sa recherche d'entreprise et assurons un suivi personnalisé tout au long de son parcours. Notre équipe pédagogique reste à votre écoute pour garantir la réussite de votre projet professionnel.</p>
                </div>
            </div>
            <div class="col-12 col-lg-6 reveal">
                <div class="alternance-avantages">
                    <div class="alternance-avantage">
                        <div class="alternance-avantage-icon"><i class="bi bi-mortarboard-fill"></i></div>
                        <div>
                            <h3 class="h5">Un diplôme reconnu</h3>
                            <p class="text-muted mb-0">Préparez un titre RNCP de niveau 3 à 5, reconnu par l'État.</p>
                        </div>
                    </div>
                    <div class="alternance-avantage">
                        <div class="alternance-avantage-icon"><i class="bi bi-cash-coin"></i></div>
                        <div>
                            <h3 class="h5">Une rémunération</h3>
                            <p class="text-muted mb-0">Percevez un salaire pendant votre formation, selon votre âge et votre niveau.</p>
                        </div>
                    </div>
                    <div class="alternance-avantage">
                        <div class="alternance-avantage-icon"><i class="bi bi-briefcase-fill"></i></div>
                        <div>
                            <h3 class="h5">Une expérience pro</h3>
                            <p class="text-muted mb-0">Acquérez une véritable expérience en entreprise, valorisée sur le marché du travail.</p>
                        </div>
                    </div>
                    <div class="alternance-avantage">
                        <div class="alternance-avantage-icon"><i class="bi bi-people-fill"></i></div>
                        <div>
                            <h3 class="h5">Un accompagnement</h3>
                            <p class="text-muted mb-0">Bénéficiez d'un suivi individualisé par nos équipes pédagogiques et administratives.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section étapes -->
<section class="alternance-etapes py-5 bg-light">
    <div class="container">
        <h2 class="h3 text-center mb-5 reveal">Les étapes de votre alternance</h2>
        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-3 reveal">
                <div class="etape-card text-center">
                    <div class="etape-numero">1</div>
                    <h3 class="h5">Candidature</h3>
                    <p class="text-muted">Déposez votre candidature en ligne ou auprès de notre équipe.</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 reveal">
                <div class="etape-card text-center">
                    <div class="etape-numero">2</div>
                    <h3 class="h5">Recherche d'entreprise</h3>
                    <p class="text-muted">Nous vous accompagnons dans la recherche de votre entreprise d'accueil.</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 reveal">
                <div class="etape-card text-center">
                    <div class="etape-numero">3</div>
                    <h3 class="h5">Signature du contrat</h3>
                    <p class="text-muted">Nous finalisons ensemble votre contrat d'apprentissage ou de professionnalisation.</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 reveal">
                <div class="etape-card text-center">
                    <div class="etape-numero">4</div>
                    <h3 class="h5">Début de la formation</h3>
                    <p class="text-muted">Votre alternance démarre : cours en centre et travail en entreprise.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="alternance-cta py-5">
    <div class="container">
        <div class="alternance-cta-card text-center reveal">
            <h2 class="h3 text-white mb-3">Prêt à vous lancer dans l'alternance ?</h2>
            <p class="text-white-50 mb-4">Contactez-nous pour échanger sur votre projet et découvrir les formations en alternance disponibles.</p>
            <a href="/contact" class="btn btn-light btn-lg"><i class="bi bi-envelope"></i> Nous contacter</a>
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
        <h2 class="h4 mt-4 mb-3">Formations en alternance</h2>
        <div class="row g-3">
            <?php foreach ($pages_liees as $p): ?>
                <div class="col-12 col-md-6 col-lg-4 reveal reveal--fade-up">
                    <a class="card card-rubrique h-100 text-decoration-none" href="<?= e('/' . $p['slug']) ?>">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <span><?= e($p['titre']) ?></span><i class="bi bi-arrow-right"></i>
                        </div>
                    </a>
                </di
v>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
