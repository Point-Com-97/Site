<?php require_once __DIR__ . '/blocs.php'; ?>
<?php require_once __DIR__ . '/../../src/config/partenaires.php'; ?>

<!-- HERO -->
<section class="hero text-center text-white">
    <div class="container py-5 hero-content">
        <h1 class="display-4 fw-bold reveal"><?= e($current_page['titre']) ?></h1>
        <p class="lead reveal"><?= e($current_page['meta_description'] ?: 'La formation tout au long de la vie') ?></p>
        <div class="d-flex flex-wrap justify-content-center gap-2 reveal">
            <a class="btn btn-outline-light btn-lg" href="/nos-formations"><i class="bi bi-book"></i> Nos formations</a>
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



<section class="partenaires container pb-4 menu-block">
    <ul class="partenaires-piste">
        <?php for ($copie = 0; $copie <= 1; $copie++): ?>
            <?php foreach (list_partenaires() as $p): ?>
                <?php if (empty($p['url'])) continue; ?>
                <li>
                    <a href="<?= e($p['url']) ?>" target="_blank" rel="noopener noreferrer"
                        <?= $copie ? 'tabindex="-1"' : '' ?>>
                        <img src="<?= e($p['image']) ?>" alt="<?= $copie ? '' : e($p['nom']) ?>"
                            height="60" loading="lazy">
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endfor; ?>
    </ul>
</section>