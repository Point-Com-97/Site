function activer_drag_drop(conteneur, selecteur_items, type, csrf_token) {
    let element_glisse = null;
    // Sélectionne tous les éléments enfants directs du conteneur correspondant au sélecteur fourni
    const items = conteneur.querySelectorAll(':scope > ' + selecteur_items);
    // Ajoute les événements de glisser-déposer à chaque élément
    items.forEach(function (ligne) {
        ligne.addEventListener('dragstart', function (event) {
            element_glisse = ligne;
        });
        // Empêche le comportement par défaut pour permettre le drop
        ligne.addEventListener('dragover', function (event) {
            event.preventDefault();
        });
        // Gère l'événement de drop pour réorganiser les éléments
        ligne.addEventListener('drop', function (event) {
            event.preventDefault();
            if (element_glisse === ligne || !element_glisse) {
                return;
            }
            const rect = ligne.getBoundingClientRect();
            const milieu = rect.top + rect.height / 2;

            // Déplace l'élément glissé avant l'élément sur lequel il est lâché
            if (event.clientY < milieu) {
                conteneur.insertBefore(element_glisse, ligne);
            } else {
                conteneur.insertBefore(element_glisse, ligne.nextSibling);
            }

            envoyer_nouvel_ordre(conteneur, selecteur_items, type, csrf_token);
        });

    });
}


// Envoie le nouvel ordre des éléments au serveur pour mise à jour
function envoyer_nouvel_ordre(conteneur, selecteur_items, type, csrf_token) {
    const lignes = conteneur.querySelectorAll(':scope > ' + selecteur_items);
    const nouvel_ordre = [];
    // Construit un tableau avec l'id et le nouvel ordre de chaque élément
    lignes.forEach(function (ligne, index) {
        nouvel_ordre.push({ id: ligne.dataset.id, ordre: index + 1, type: type, csrf_token: csrf_token });
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

const menuList = document.getElementById('menu-list');
if (menuList) {
    activer_drag_drop(menuList, '.list-group[draggable="true"]', 'menu', menuList.dataset.csrf_token);
}

document.querySelectorAll('.child-group').forEach(function (groupe) {
    activer_drag_drop(groupe, '.container[draggable="true"]', 'page', groupe.dataset.csrf_token);
});

