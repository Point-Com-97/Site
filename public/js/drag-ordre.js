function activer_drag_drop(conteneur, selecteur_items) {
    let element_glisse = null;
    // Sélectionne tous les éléments enfants directs du conteneur correspondant au sélecteur fourni
    const items = conteneur.querySelectorAll(':scope > ' + selecteur_items);
    // Ajoute les événements de glisser-déposer à chaque élément
    items.forEach(function(ligne) {
        ligne.addEventListener('dragstart', function(event) {
            element_glisse = ligne;
        });
        // Empêche le comportement par défaut pour permettre le drop
        ligne.addEventListener('dragover', function(event) {
            event.preventDefault();
        });
        // Gère l'événement de drop pour réorganiser les éléments
        ligne.addEventListener('drop', function(event) {
            event.preventDefault();
            if (element_glisse === ligne || !element_glisse) {
                return;
            }
        // Déplace l'élément glissé avant l'élément sur lequel il est lâché
            conteneur.insertBefore(element_glisse, ligne);
            envoyer_nouvel_ordre(conteneur, selecteur_items);
        });
    });
}

// Envoie le nouvel ordre des éléments au serveur pour mise à jour
function envoyer_nouvel_ordre(conteneur, selecteur_items) {
    const lignes = conteneur.querySelectorAll(':scope > ' + selecteur_items);
    const nouvel_ordre = [];
// Construit un tableau avec l'id et le nouvel ordre de chaque élément
    lignes.forEach(function(ligne, index) {
        nouvel_ordre.push({ id: ligne.dataset.id, ordre: index + 1 });
    });
// Envoie le nouvel ordre au serveur via une requête POST
    fetch('/admin/endpoint/drag-drop.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(nouvel_ordre)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                show_message('Ordre mis à jour', 'success');
            } else {
                show_message('Échec de la mise à jour de l\'ordre');
            }
        });
}

activer_drag_drop(document.getElementById('menu-list'), '.list-group[draggable="true"]');

document.querySelectorAll('.child-group').forEach(function(groupe) {
    activer_drag_drop(groupe, '.container[draggable="true"]');
});
