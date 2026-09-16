<?php require __DIR__ . '/../auth-check.php';

require_once __DIR__ . '/../../templates/admin/header.php';

require_once __DIR__ . '/../../../src/php/Settings.php';

$new_settings = new Settings();
$settings = $new_settings->get();
?>

<form method="post" class="settings_form" id="settings_form">
    <label>Couleur primaire</label>
    <input type="color" name="couleur_primaire" value="<?= htmlspecialchars($settings['couleur_primaire'] ?? '#019DD4') ?>">

    <label>Couleur secondaire</label>
    <input type="color" name="couleur_secondaire" value="<?= htmlspecialchars($settings['couleur_secondaire'] ?? '#EF8B2B') ?>">

    <label>Couleur tertiaire</label>
    <input type="color" name="couleur_tertiaire" value="<?= htmlspecialchars($settings['couleur_tertiaire'] ?? '#422774') ?>">

    <label>Couleur texte bouton</label>
    <input type="color" name="couleur_texte_bouton" value="<?= htmlspecialchars($settings['couleur_texte_bouton'] ?? '#FFFFFF') ?>">

    <label>Couleur lien</label>
    <input type="color" name="couleur_lien" value="<?= htmlspecialchars($settings['couleur_lien'] ?? '#019DD4') ?>">

    <label>Police du corps</label>
    <input type="text" name="police_corps" value="<?= htmlspecialchars($settings['police_corps'] ?? 'Crimson Pro') ?>">

    <label>CSS personnalisé</label>
    <textarea name="css_personnalise" class="form-control" rows="10"><?= htmlspecialchars($settings['css_personnalise'] ?? '') ?></textarea>

    <button type="submit" class="btn btn-primary">Enregistrer</button>
</form>

<?php require_once __DIR__ . '/../../templates/footer.php'; ?>