<?php require_once __DIR__ . '/blocs.php'; ?>
<?php require_once __DIR__ . '/../../src/config/partenaires.php'; ?>

<!-- HERO -->
<section class="hero text-center text-white">
    <div class="container py-5 hero-content">
        <h1 class="display-4 fw-bold reveal"><?= e($current_page['titre']) ?></h1>
        <p class="lead reveal"><?= e($current_page['meta_description'] ?: 'La formation tout au long de la vie') ?></p>
        <div class="d-flex flex-wrap justify-content-center gap-2 reveal">
            <a class="btn btn-outline-light btn-lg" href="/formation"><i class="bi bi-book"></i> Nos formations</a>
            <a class="btn btn-outline-light btn-lg" href="/contact"><i class="bi bi-envelope"></i> Nous contacter</a>
        </div>
    </div>
</section>

<!-- RUBRIQUES : une carte par menu, générée depuis menus.php -->
<section class="container py-5">
    <div class="row g-4">
        <?php foreach (list_menus() as $cle => $m):
            if (!isset($m['page']) || $m['page'] === 'centre-de-formation' || $m['page'] === 'contact') continue; ?>
            <div class="col-12 col-sm-6 col-lg-4 reveal">
                <a class="card card-rubrique h-100 text-decoration-none" href="/<?= e($m['page']) ?>">
                    <div class="card-body text-center">
                        <i class="bi <?= e($m['icone']) ?> display-4 text-primary"></i>
                        <h2 class="h5 mt-3"><?= e($m['label']) ?></h2>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- CONTENU ÉDITÉ DANS L'ADMIN (blocs de la page centre-de-formation) -->
<section class="container pb-2">
    <?php render_blocs($all_blocs); ?>
</section>


<!-- CONTENU ÉDITÉ DANS L'ADMIN (blocs de la page centre-de-formation) -->
<section class="partenaire container pb-1">
    <div class="menu-block">
        <h2 class="menu-title">Tous nos partenaires</h2>
        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach (list_partenaires() as $cle => $p): ?>
                    <div class="carousel-item <?= e($p['class']) ?>">
                        <img src="<?= e($p['image']) ?>" class="d-block" alt="<?= e($p['nom']) ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>