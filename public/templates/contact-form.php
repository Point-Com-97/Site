<?php
// Jeton CSRF + heure d'affichage (anti-robot : un humain met plus de 3 s à remplir)
require_once __DIR__ . '/../../src/php/Csrf.php';
$_SESSION['contact_time'] = time();
?>
<div class="contact-form-wrapper reveal">
    <div class="contact-form-card">
        <div class="contact-form-header text-center mb-4">
            <div class="contact-form-icon">
                <i class="bi bi-envelope-heart-fill"></i>
            </div>
            <h2 class="h4 mb-2">Nous contacter</h2>
            <p class="text-muted mb-0">Une question, un projet ? Écrivez-nous, nous vous répondrons dans les plus brefs délais.</p>
        </div>

        <form class="contact-form" id="contact-form" method="post" action="/contact-send.php">
            <input type="hidden" name="csrf_token" value="<?= e(generer_csrf_token()) ?>">
            <!-- Honeypot : caché aux humains, rempli par les robots -->
            <div class="hp-field" style="position:absolute;left:-9999px;top:-9999px;width:1px;height:1px;overflow:hidden;opacity:0" aria-hidden="true">
                <label>Ne pas remplir <input type="text" name="site_web" tabindex="-1" autocomplete="off" aria-hidden="true"></label>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="form-floating">
                        <input class="form-control" id="c-nom" name="nom" maxlength="100" placeholder="Votre nom" required>
                        <label for="c-nom"><i class="bi bi-person-fill me-1"></i> Nom</label>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-floating">
                        <input class="form-control" id="c-email" type="email" name="email" maxlength="150" placeholder="Votre e-mail" required>
                        <label for="c-email"><i class="bi bi-envelope-fill me-1"></i> E-mail</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <textarea class="form-control" id="c-msg" name="message" rows="5" maxlength="3000" placeholder="Votre message" style="height: 150px" required></textarea>
                        <label for="c-msg"><i class="bi bi-chat-left-text-fill me-1"></i> Message</label>
                    </div>
                </div>
            </div>

            <div class="contact-form-footer mt-4">
                <p class="contact-form-note">
                    <i class="bi bi-shield-check me-1"></i>
                    Les informations saisies servent uniquement à répondre à votre demande et ne sont pas conservées sur le site.
                </p>
                <button class="btn btn-primary btn-lg contact-form-submit w-100" type="submit">
                    <i class="bi bi-send-fill me-2"></i> Envoyer le message
                </button>
            </div>
        </form>
    </div>

    <!-- Coordonnées de contact à côté -->
    <div class="contact-info-card reveal">
        <h3 class="h5 mb-4"><i class="bi bi-info-circle-fill me-2"></i> Nos coordonnées</h3>

        <div class="contact-info-item">
            <div class="contact-info-icon"><i class="bi bi-telephone-fill"></i></div>
            <div>
                <strong>Pôle de Cayenne</strong><br>
                <a href="tel:+594313724">05 94 31 37 24</a>
            </div>
        </div>

        <div class="contact-info-item">
            <div class="contact-info-icon"><i class="bi bi-telephone-fill"></i></div>
            <div>
                <strong>Pôle de Matoury</strong><br>
                <a href="tel:+594281905">05 94 28 19 05</a>
            </div>
        </div>

        <div class="contact-info-item">
            <div class="contact-info-icon"><i class="bi bi-envelope-fill"></i></div>
            <div>
                <strong>E-mail</strong><br>
                <a href="mailto:contact@cfa-pcom.fr">contact@cfa-pcom.fr</a>
            </div>
        </div>

        <div class="contact-info-item">
            <div class="contact-info-icon"><i class="bi bi-geo-alt-fill"></i></div>
            <div>
                <strong>Adresse</strong><br>
                77 Rue René Jadfard, 97300 Cayenne<br>
                5 rue Orapus, 97351 Matoury
            </div>
        </div>

        <div class="contact-info-item">
            <div class="contact-info-icon"><i class="bi bi-clock-fill"></i></div>
            <div>
                <strong>Horaires</strong><br>
                Lun : 08:30 - 12:30<br>
                Mar - Ven : 08:30 - 12:30 / 13:30 - 16:15
            </div>
        </div>
    </div>
</div>
