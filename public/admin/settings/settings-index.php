<title>Gestion CSS</title>
<?php require __DIR__ . '/../auth-check.php';

require_once __DIR__ . '/../../templates/admin/header.php';

require_once __DIR__ . '/../../../src/php/Settings.php';

require_once __DIR__ . '/../../../src/php/Csrf.php';

$new_settings = new Settings();
$settings = $new_settings->get();
$csrf_token = generer_csrf_token();
?>

<div class="container py-3">
    <form method="post" class="settings_form row g-3" id="settings_form">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

        <div class="col-6 col-md-4">
            <label for="couleur_primaire" class="form-label">Couleur primaire</label>
            <input type="color" id="couleur_primaire" name="couleur_primaire"
                class="form-control form-control-color w-100"
                value="<?= htmlspecialchars($settings['couleur_primaire'] ?? '#019DD4') ?>">
        </div>

        <div class="col-6 col-md-4">
            <label for="couleur_secondaire" class="form-label">Couleur secondaire</label>
            <input type="color" id="couleur_secondaire" name="couleur_secondaire"
                class="form-control form-control-color w-100"
                value="<?= htmlspecialchars($settings['couleur_secondaire'] ?? '#EF8B2B') ?>">
        </div>

        <div class="col-6 col-md-4">
            <label for="couleur_tertiaire" class="form-label">Couleur tertiaire</label>
            <input type="color" id="couleur_tertiaire" name="couleur_tertiaire"
                class="form-control form-control-color w-100"
                value="<?= htmlspecialchars($settings['couleur_tertiaire'] ?? '#422774') ?>">
        </div>

        <div class="col-6 col-md-4">
            <label for="couleur_texte_bouton" class="form-label">Couleur texte bouton</label>
            <input type="color" id="couleur_texte_bouton" name="couleur_texte_bouton"
                class="form-control form-control-color w-100"
                value="<?= htmlspecialchars($settings['couleur_texte_bouton'] ?? '#FFFFFF') ?>">
        </div>

        <div class="col-6 col-md-4">
            <label for="couleur_lien" class="form-label">Couleur lien</label>
            <input type="color" id="couleur_lien" name="couleur_lien"
                class="form-control form-control-color w-100"
                value="<?= htmlspecialchars($settings['couleur_lien'] ?? '#019DD4') ?>">
        </div>

        <div class="col-12 col-md-4">
            <label for="police_corps" class="form-label">Police du corps</label>
            <input type="text" id="police_corps" name="police_corps" class="form-control"
                value="<?= htmlspecialchars($settings['police_corps'] ?? 'Crimson Pro') ?>">
        </div>

        <div class="col-12">
            <label for="css_personnalise" class="form-label">CSS personnalisé</label>
            <textarea id="css_personnalise" name="css_personnalise" class="form-control" rows="10"><?= htmlspecialchars($settings['css_personnalise'] ?? '') ?></textarea>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../templates/footer.php'; ?>