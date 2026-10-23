<?php
// Template spécifique : Bilan de compétences
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

<!-- Section présentation bilan -->
<section class="bilan-section py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-12 col-lg-6 reveal">
                <div class="bilan-content">
                    <h2 class="h3 mb-4">Faites le point sur votre carrière</h2>
                    <p class="lead">Le bilan de compétences est un dispositif qui vous permet d'analyser vos compétences professionnelles et personnelles, vos aptitudes et vos motivations, afin de définir un projet professionnel ou de formation qui vous ressemble.</p>
                    <p>Encadré par un consultant spécialisé, il se déroule en plusieurs entretiens sur une durée de 24 heures, réparties sur 2 à 3 mois. Il est finançable par le CPF (Compte Personnel de Formation).</p>
                </div>
            </div>
            <div class="col-12 col-lg-6 reveal">
                <div class="bilan-avantages">
                    <div class="bilan-avantage">
                        <div class="bilan-avantage-icon"><i class="bi bi-clipboard-check-fill"></i></div>
                        <div>
                            <h3 class="h5">Identifier vos compétences</h3>
                            <p class="text-muted mb-0">Faites le point sur vos acquis professionnels et personnels.</p>
                        </div>
                    </div>
                    <div class="bilan-avantage">
                        <div class="bilan-avantage-icon"><i class="bi bi-compass-fill"></i></div>
                        <div>
                            <h3 class="h5">Définir votre projet</h3>
                            <p class="text-muted mb-0">Construisez un projet professionnel réaliste et motivant.</p>
                        </div>
                    </div>
                    <div class="bilan-avantage">
                        <div class="bilan-avantage-icon"><i class="bi bi-graph-up-arrow"></i></div>
                        <div>
                            <h3 class="h5">Évoluer sereinement</h3>
                            <p class="text-muted mb-0">Préparez une reconversion ou une évolution en toute confiance.</p>
                        </div>
                    </div>
                    <div class="bilan-avantage">
                        <div class="bilan-avantage-icon"><i class="bi bi-wallet-fill"></i></div>
                        <div>
                            <h3 class="h5">Financement CPF</h3>
                            <p class="text-muted mb-0">Le bilan de compétences est finançable via votre Compte Personnel de Formation.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Déroulement -->
<section class="bilan-deroulement py-5 bg-light">
    <div class="container">
        <h2 class="h3 text-center mb-5 reveal">Comment se déroule le bilan ?</h2>
        <div class="row g-4">
            <div class="col-12 col-md-4 reveal">
                <div class="deroulement-card text-center">
                    <div class="deroulement-icon"><i class="bi bi-chat-square-text-fill"></i></div>
                    <div class="deroulement-duree">Phase 1</div>
                    <h3 class="h5">Entretien préliminaire</h3>
                    <p class="text-muted">Analyse de votre situation, de vos attentes et définition de vos besoins.</p>
                </div>
            </div>
            <div class="col-12 col-md-4 reveal">
                <div class="deroulement-card text-center">
                    <div class="deroulement-icon"><i class="bi bi-search-heart-fill"></i></div>
                    <div class="deroulement-duree">Phase 2</div>
                    <h3 class="h5">Investigation</h3>
                    <p class="text-muted">Exploration de vos compétences, tests, enquêtes métiers et rencontres professionnelles.</p>
                </div>
            </div>
            <div class="col-12 col-md-4 reveal">
                <div class="deroulement-card text-center">
                    <div class="deroulement-icon"><i class="bi bi-flag-fill"></i></div>
                    <div class="deroulement-duree">Phase 3</div>
                    <h3 class="h5">Conclusion</h3>
                    <p class="text-muted">Synthèse des résultats et construction de votre projet professionnel ou de formation.</p>
                </div>
            </div>
        </div>
        <p class="text-center text-muted mt-4 reveal">Durée totale : 24 heures, réparties sur 2 à 3 mois</p>
    </div>
</section>

<!-- Publics concernés -->
<section class="bilan-publics py-5">
    <div class="container">
        <h2 class="h3 text-center mb-5 reveal">Le bilan de compétences est fait pour vous si...</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-12 col-md-6 col-lg-4 reveal">
                <div class="public-card">
                    <i class="bi bi-arrow-repeat"></i>
                    <h3 class="h5">Vous envisagez une reconversion</h3>
                    <p class="text-muted mb-0">Vous souhaitez changer de métier ou de secteur d'activité.</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4 reveal">
                <div class="public-card">
                    <i class="bi bi-ladder"></i>
                    <h3 class="h5">Vous visez une promotion</h3>
                    <p class="text-muted mb-0">Vous souhaitez évoluer vers des responsabilités supérieures.</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4 reveal">
                <div class="public-card">
                    <i class="bi bi-lightbulb-fill"></i>
                    <h3 class="h5">Vous créez votre entreprise</h3>
                    <p class="text-muted mb-0">Vous avez un projet entrepreneurial et souhaitez valider vos compétences.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="bilan-cta py-5">
    <div class="container">
        <div class="bilan-cta-card text-center reveal">
            <h2 class="h3 text-white mb-3">Prêt à faire le bilan ?</h2>
            <p class="text-white-50 mb-4">Prenez rendez-vous avec l'un de nos conseillers pour échanger sur votre projet et démarrer votre bilan de compétences.</p>
            <a href="/contact" class="btn btn-light btn-lg"><i class="bi bi-calendar-check"></i> Prendre rendez-vous</a>
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
        <h2 class="h4 mt-4 mb-3">En savoir plus</h2>
        <div class="row g-3">
            <?php foreach ($pages_liees as $p): ?>
                <div class="col-12 col-md-6 col-lg-4 reveal reveal--fade-up">
                    <a class="card card-rubrique h-100 text-decoration-none" href="<?= e('/' . $p['slug']) ?>">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <span><?= e($p['titre']) ?></span><i class="bi bi-arrow-right"></i>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
