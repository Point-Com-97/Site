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
                    <p class="footer-text mb-1"><i class="bi bi-telephone-fill"></i><a href="tel:+594313724"> 05 94 31 37 24</a></p>
                    <p class="footer-text mb-0"><i class="bi bi-geo-alt-fill"></i> 77 Rue René Jadfard 97300 Cayenne</p>
                    <p class="footer-text mb-0"><i class="bi bi-envelope-fill"></i> <a href="mailto:contact@cfa-pcom.fr">contact@cfa-pcom.fr</a></p>

                    <h5 class="footer-title pt-3"><i class="bi bi-building-fill"></i>Centre de Matoury </h5>
                    <p class="footer-text mb-1"><i class="bi bi-telephone-fill"></i><a href="tel:+594281905"> 05 94 28 19 05</a></p>
                    <p class="footer-text mb-0 pt-1"><i class="bi bi-geo-alt-fill"></i> 5 rue Orapus – Parc d’Activité Horizon 97351 Matoury</p>
                    <p class="footer-text mb-0"><i class="bi bi-envelope-fill"></i> <a href="mailto:pcb@cfa-pcom.fr">pcb@cfa-pcom.fr</a></p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="footer-block">
                    <h5 class="footer-title"><i class="bi bi-clock-fill"></i>Horaires</h5>
                    <p class="footer-text mb-1">Lundi : 08:30 - 12:00</p>
                    <p class="footer-text mb-1">Mardi - Vendredi</p>
                    <p class="footer-text mb-1"> Matin : 08:30 - 12:00</p>
                    <p class="footer-text mb-1"> Après-midi : 13:30 - 16:00</p>
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
                        <a href="https://www.ctguyane.fr/">COLLECTIVITE TERRITORIALE DE LA GUYANE</a>
                        <a href="https://www.akto.fr/">AKTO</a>
                        <a href="https://www.francetravail.fr/accueil/" >FRANCE TRAVAIL</a>
                        <a href="https://www.ocapiat.fr/">OCAPIAT</a>
                        <a href="https://missionlocaleguyane.fr/" >MISSION LOCALE GUYANE</a>
                        <a href="https://travail-emploi.gouv.fr/formation-professionnelle/acteurs-cadre-et-qualite-de-la-formation-professionnelle/article/qualiopi-marque-de-certification-qualite-des-prestataires-de-formation" class="link">QUALIOPI</a>
                        <a href="https://www.opcoep.fr/">OPCO</a>
                        <a href="https://lesentreprises-sengagent.gouv.fr/">LES ENTREPRISES S'ENGAGENT</a>
                        <a href="https://immersion-facile.beta.gouv.fr/">IMMERSION FACILITÉE</a>
                        <a href="https://www.croix-rouge.fr/">CROIX ROUGE FRANÇAISE</a>
                        <a href="https://dreets.gouv.fr/">DREETS</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="footer-block">
                    <p class="footer-title fs-5"><i class="bi bi-patch-check-fill"></i>Centre de formation agréé</p>
                    <a class="footer-text"
                        href="https://certifopac.fr/qualiopi/certification/verification/?siren=440640456"
                        target="_blank" rel="noopener noreferrer"><img src="/assets/image/qualiopi.png" class="img-fluid" alt="Certification Qualiopi"></a>
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

<!-- Tester que la fonction flash existe avant de l'appeler  -->
<?php if ($flash): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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