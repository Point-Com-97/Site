<?php
// Template spécifique : RHF Inclusion
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

<!-- Section engagement -->
<section class="inclusion-section py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-12 col-lg-6 reveal">
                <div class="inclusion-content">
                    <h2 class="h3 mb-4">Notre engagement pour l'inclusion</h2>
                    <p class="lead">Chez Point Com, nous croyons que la formation doit être accessible à toutes et à tous, sans distinction. C'est pourquoi nous avons fait de l'inclusion un pilier fondamental de notre action.</p>
                    <p>Le dispositif RHF (Reconnaissance de la Qualité de Travailleur Handicapé) Inclusion traduit notre volonté de rendre nos formations accessibles aux personnes en situation de handicap, en adaptant nos parcours, nos locaux et nos méthodes pédagogiques.</p>
                    <p>Notre équipe formée accompagne chaque apprenant selon ses besoins spécifiques, pour garantir l'égalité des chances et la réussite de chacun.</p>
                </div>
            </div>
            <div class="col-12 col-lg-6 reveal">
                <div class="inclusion-piliers">
                    <div class="inclusion-pilier">
                        <div class="inclusion-pilier-icon"><i class="bi bi-universal-access-circle"></i></div>
                        <div>
                            <h3 class="h5">Accessibilité</h3>
                            <p class="text-muted mb-0">Locaux adaptés, supports pédagogiques accessibles et outils numériques inclusifs.</p>
                        </div>
                    </div>
                    <div class="inclusion-pilier">
                        <div class="inclusion-pilier-icon"><i class="bi bi-person-check-fill"></i></div>
                        <div>
                            <h3 class="h5">Accompagnement personnalisé</h3>
                            <p class="text-muted mb-0">Un référent handicap dédié pour un suivi individualisé tout au long du parcours.</p>
                        </div>
                    </div>
                    <div class="inclusion-pilier">
                        <div class="inclusion-pilier-icon"><i class="bi bi-heart-fill"></i></div>
                        <div>
                            <h3 class="h5">Bienveillance</h3>
                            <p class="text-muted mb-0">Un environnement bienveillant où chacun progresse à son rythme, dans le respect de ses différences.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Chiffres clés inclusion -->
<section class="inclusion-chiffres py-5 bg-light">
    <div class="container">
        <h2 class="h3 text-center mb-5 reveal">L'inclusion en chiffres</h2>
        <div class="row g-4 text-center">
            <div class="col-6 col-lg-3 reveal">
                <div class="chiffre-card">
                    <div class="chiffre-nombre">100%</div>
                    <p class="chiffre-label">de nos centres accessibles</p>
                </div>
            </div>
            <div class="col-6 col-lg-3 reveal">
                <div class="chiffre-card">
                    <div class="chiffre-nombre">RHF</div>
                    <p class="chiffre-label">Reconnaissance qualité handicap</p>
                </div>
            </div>
            <div class="col-6 col-lg-3 reveal">
                <div class="chiffre-card">
                    <div class="chiffre-nombre">1</div>
                    <p class="chiffre-label">Référent handicap dédié par site</p>
                </div>
            </div>
            <div class="col-6 col-lg-3 reveal">
                <div class="chiffre-card">
                    <div class="chiffre-nombre">0€</div>
                    <p class="chiffre-label">Frais liés au handicap à la charge de l'apprenant</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="inclusion-cta py-5">
    <div class="container">
        <div class="inclusion-cta-card text-center reveal">
            <h2 class="h3 text-white mb-3">Une question sur l'accessibilité de nos formations ?</h2>
            <p class="text-white-50 mb-4">Notre équipe est à votre écoute pour étudier ensemble votre situation et vous proposer un parcours adapté.</p>
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
