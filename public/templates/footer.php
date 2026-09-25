<?php
require_once __DIR__ . '/../../src/php/Flash.php';

$flash = get_flash();
?>

</main>
<footer class="footer">
    <div class="container py-4">
        <div class="row g-3 footer-grid">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="footer-block">
                    <h5 class="footer-title"><i class="bi bi-building-fill"></i>Centre de Cayenne :</h5>
                    <p class="footer-text mb-1"><i class="bi bi-telephone-fill"></i><a href="tel:0594313724"> 05 94 31 37 24</a></p>
                    <p class='footer-text mb-0'><i class="bi bi-geo-alt-fill"></i> 77 Rue Réne Jadfard 97300 Cayenne</p>
                    <p class="footer-text mb-0"><i class="bi bi-envelope-fill"></i> <a href="mailto:contact@cfa-pcom.fr">contact@cfa-pcom.fr</a></p>

                    <h5 class="footer-title pt-3"><i class="bi bi-building-fill"></i> Centre de Matoury </h5>
                    <p class="footer-text mb-1"><i class="bi bi-telephone-fill"></i><a href="tel:0594281905"> 05 94 28 19 05</a></p>
                    <p class='footer-text mb-0 pt-1'><i class="bi bi-geo-alt-fill"></i> 5 rue Orapus – Parc d’Activité Horizon 97351 Matoury</p>
                    <p class="footer-text mb-0"><i class="bi bi-envelope-fill"></i> <a href="mailto:pcb@cfa-pcom.fr">pcb@cfa-pcom.fr</a></p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="footer-block">
                    <h5 class="footer-title"><i class="bi bi-clock-fill"></i> Horaires</h5>
                    <p class="footer-text mb-1">Lundi : 08:30 - 12:00</p>
                    <p class="footer-text mb-1">Mardi - Vendredi</p>
                    <p class="footer-text mb-1"> Matin : 08:30 - 12:00</p>
                    <p class="footer-text mb-1"> Après-midi : 13:30 - 16:00</p><br>
                    <h5 class="footer-title"><i class="bi bi-share-fill"></i> Reseaux sociaux</h5>
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
                    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="/assets/image/AKTO.png" class="d-block w-100" alt="AKTO">
                            </div>
                            <div class="carousel-item">
                                <img src="/assets/image/FT.png" class="d-block w-100" alt="FRANCE TRAVAIL">
                            </div>
                            <div class="carousel-item">
                                <img src="/assets/image/EPNAK.png" class="d-block w-100" alt="EPNAK">
                            </div>
                            <div class="carousel-item">
                                <img src="/assets/image/CTG.png" class="d-block w-100" alt="CTG">
                            </div>
                            <div class="carousel-item">
                                <img src="/assets/image/OCAPIAT.png" class="d-block w-100" alt="OCAPIAT">
                            </div>
                            <div class="carousel-item">
                                <img src="/assets/image/MLG.png" class="d-block w-100" alt="MISSION LOCALE">
                            </div>
                            <div class="carousel-item">
                                <img src="/assets/image/QV2.png" class="d-block w-100" alt="QUALIOPI">
                            </div>
                            <div class="carousel-item">
                                <img src="/assets/image/OPCO.png" class="d-block w-100" alt="OPCO">
                            </div>
                            <div class="carousel-item">
                                <img src="/assets/image/HANDI.png" class="d-block w-100" alt="HANDI BIENVEILLANT">
                            </div>
                            <div class="carousel-item">
                                <img src="/assets/image/LES.jpg" class="d-block w-100" alt="LES ENTREPRISES S'ENGAGENT">
                            </div>
                            <div class="carousel-item">
                                <img src="/assets/image/ISF.jpg" class="d-block w-100" alt="IMMERSION FACILITÉE">
                            </div>
                            <div class="carousel-item">
                                <img src="/assets/image/CR.jpg" class="d-block w-100" alt="CROIX ROUGE FRANÇAISE">
                            </div>
                            <div class="carousel-item">
                                <img src="/assets/image/DREETS.jpg" class="d-block w-100" alt="DREETS">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="footer-block">
                    <h5 class="footer-title fs-4"><i class="bi bi-patch-check-fill"></i>Certification Qualiopi</h5>
                    <a class="footer-text"
                        href="https://certifopac.fr/qualiopi/certification/verification/?siren=440640456"
                        target="_blank" rel="noopener noreferrer"><img src="/assets/image/qualiopi.png" class="img-fluid"></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="/js/bootstrap.bundle.js"></script>
<?php if (!empty($_SESSION['admin_id'])) : ?>
    <script src="/js/toast.js"></script>
    <script src="/assets/vendor/quill/quill.js"></script>
    <script src="/js/media.js"></script>
    <script src="/js/dashboard.js"></script>
    <script src="/js/drag-ordre.js"></script>
    <script src="/js/editor.js"></script>
    <script src="/js/settings.js"></script>
<?php endif; ?>

<?php if ($flash): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            show_message(<?= json_encode($flash['message']) ?>, <?= json_encode($flash['type']) ?>);
        });
    </script>
<?php endif; ?>
</body>

</html>