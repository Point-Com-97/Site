<?php
require_once __DIR__ . '/../../src/php/Flash.php';
require_once __DIR__ . '/../../src/config/menus.php';
require_once __DIR__ . '/../../src/config/partenaires.php';
require_once __DIR__ . '/../../src/php/helpers.php';

$flash = get_flash();
?>

</main>
<footer class="footer">
    <div class="container py-4">
        <div class="row g-3 footer-grid">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="footer-block">
                    <h5 class="footer-title"><i class="bi bi-building-fill"></i>Pôle de Cayenne :</h5>
                    <p class="footer-text mb-1"><i class="bi bi-telephone-fill"></i><a href="tel:+594313724"> 05 94 31 37 24</a></p>
                    <a class="footer-text mb-0" href="https://maps.app.goo.gl/Q2CUJc5bFmXquZ7i8" target="_blank" rel="noopener noreferrer"><i class="bi bi-geo-alt-fill"></i> 77 Rue René Jadfard 97300 Cayenne</a>
                    <p class="footer-text mb-0"><i class="bi bi-envelope-fill"></i> <a href="mailto:contact@cfa-pcom.fr">contact@cfa-pcom.fr</a></p>

                    <h5 class="footer-title pt-3"><i class="bi bi-building-fill"></i>Pôle de Matoury </h5>
                    <p class="footer-text mb-1"><i class="bi bi-telephone-fill"></i><a href="tel:+594281905"> 05 94 28 19 05</a></p>
                    <a class="footer-text mb-0 pt-1" href="https://maps.app.goo.gl/zFjF2v5456S49M9m9" target="_blank" rel="noopener noreferrer"><i class="bi bi-geo-alt-fill"></i> 5 rue Orapus – Parc d’Activité Horizon 97351 Matoury</a>
                    <p class="footer-text mb-0"><i class="bi bi-envelope-fill"></i> <a href="mailto:pcb@cfa-pcom.fr">pcb@cfa-pcom.fr</a></p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="footer-block">
                    <h5 class="footer-title"><i class="bi bi-clock-fill"></i>Horaires</h5>
                    <p class="footer-text mb-1">Lundi : 08:30 - 12:30</p>
                    <p class="footer-text mb-1">Mardi - Vendredi</p>
                    <p class="footer-text mb-1"> Matin : 08:30 - 12:30</p>
                    <p class="footer-text mb-1"> Après-midi : 13:30 - 16:15</p>
                    <h5 class="footer-title pt-3"><i class="bi bi-share-fill"></i> Réseaux sociaux</h5>
                    <div class="social-links">
                        <a href="https://www.facebook.com/association.pointcom" target="_blank"
                            rel="noopener noreferrer"><i class="bi bi-facebook"></i> FACEBOOK</a>
                        <a href="https://www.instagram.com/point_com_/" target="_blank" rel="noopener noreferrer"><i class="bi bi-instagram"></i> INSTAGRAM</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="footer-block">
                    <h5 class="footer-title"><i class="bi bi-buildings-fill"></i>Nos Partenaires</h5>
                    <div class="list-group">
                        <?php foreach (list_partenaires() as $cle => $p): ?>
                            <?php if (!$p['url']): ?>
                                <span><?= e($p['nom']) ?></span>
                            <?php else: ?>
                                <a href="<?= e($p['url']) ?> "target="_blank" rel="noopener noreferrer"><?= e($p['nom']) ?></a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="footer-block">
                    <p class="footer-title fs-5"><i class="bi bi-patch-check-fill"></i>Centre de formation agréé</p>
                    <a class="footer-text"
                        href="https://certifopac.fr/qualiopi/certification/verification/?siren=440640456"
                        target="_blank" rel="noopener noreferrer"><img src="/assets/image/QV2.png" class="img-fluid" alt="Certification Qualiopi"></a>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="footer-block">
                    <h5 class="footer-title"><i class="bi bi-info-square-fill"></i>A propos</h5>
                    <?php foreach (list_pages_footer() as $cle => $p):
                        $href = '/' . $p['page'];
                    ?>
                        <p class="footer-text mb-1"><a href="<?= e($href) ?>"> <?= e($p['label']) ?></a></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="/js/bootstrap.bundle.js"></script>
<script src="/js/search.js"></script>
<script src="/js/public.js" defer></script>
<?php if (!empty($_SESSION['admin_id'])) : ?>
    <script src="/js/toast.js"></script>
    <script src="/assets/vendor/quill/quill.js"></script>
    <script src="/js/media.js"></script>
    <script src="/js/dashboard.js"></script>
    <script src="/js/drag-ordre.js"></script>
    <script src="/js/editor.js"></script>
<?php endif; ?>

<!-- Tester que la fonction flash existe avant de l'appeler  -->
<?php if ($flash): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof show_message === 'function') {
                show_message(
                    <?= json_encode($flash['message'], JSON_HEX_TAG | JSON_HEX_AMP) ?>,
                    <?= json_encode($flash['type']) ?>
                );
            }
        });
    </script>
<?php endif; ?>
</body>

</html>