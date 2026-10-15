<?php
// Jeton CSRF + heure d'affichage (anti-robot : un humain met plus de 3 s à remplir)
require_once __DIR__ . '/../../src/php/Csrf.php';
$_SESSION['contact_time'] = time();
?>
<form class="mx-auto my-4" style="max-width:600px" id="contact-form" method="post" action="/contact-send.php">
    <input type="hidden" name="csrf_token" value="<?= e(generer_csrf_token()) ?>">
    <!-- Honeypot : caché aux humains, rempli par les robots -->
    <div style="position:absolute;left:-9999px" aria-hidden="true">
        <label>Ne pas remplir <input type="text" name="site_web" tabindex="-1" autocomplete="off"></label>
    </div>

    <div class="mb-3"><label class="form-label" for="c-nom">Nom</label>
        <input class="form-control" id="c-nom" name="nom" maxlength="100" required></div>
    <div class="mb-3"><label class="form-label" for="c-email">E-mail</label>
        <input class="form-control" id="c-email" type="email" name="email" maxlength="150" required></div>
    <div class="mb-3"><label class="form-label" for="c-msg">Message</label>
        <textarea class="form-control" id="c-msg" name="message" rows="5" maxlength="3000" required></textarea></div>

    <p class="small text-muted">Les informations saisies servent uniquement à répondre à votre demande et ne sont pas conservées sur le site.</p>
    <button class="btn btn-primary" type="submit"><i class="bi bi-send"></i> Envoyer</button>
</form>
