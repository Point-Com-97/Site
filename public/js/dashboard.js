function remove_items(id, type) {
    if (!confirm('Supprimer ce menu définitivement ?')) {
        return;
    }
    fetch(`/admin/endpoint/delete.php?id=${id}&type=${type}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                show_message('Suppression effectuée.', 'success');
                if (type == 'Menu') {
                    const menu = document.querySelector(`[data-id="menu_${id}"]`);  // querySelector peut bloquer l'éxecution des fonctions
                    menu.remove();
                } else {
                    const menu = document.querySelector(`[data-id="page_${id}"]`);  // querySelector peut bloquer l'éxecution des fonctions
                    menu.remove();
                }

            } else {
                show_message('La suppression a échoué.');
            }
        });
}

function edit_menu(id, titre) {
    fetch(`/admin/endpoint/update.php?id=${id}&menu=${titre}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                show_message('Mise à jour réussie', 'success');

                const title = document.querySelector(`#menu_label_${id}`);
                title.textContent = titre;

                const modalElement = document.querySelector(`#editModal${id}`);
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                modalInstance.hide();
            } else {
                show_message('Echec de la modification');
            }
        });
}

document.querySelectorAll('.edit_form').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const data = new FormData(form);
        edit_menu(data.get('id'), data.get('titre'));
    });
});

function add_menu(titre, menu_id) {
    fetch(`/admin/endpoint/create.php?titre=${titre}&menu_id=${menu_id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                show_message('Menu ajouter', 'success');
                if (!parent_id) {
                    const body = document.querySelector('#menu-list');
                    // Insert les bloc html pour la modal et le menu à la fin du body
                    body.insertAdjacentHTML('beforeend', data.html);

                    const modalElement = document.querySelector(`#new_menu`);
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    modalInstance.hide();

                } else {
                    const parent = document.querySelector(`[data-id="${parent_id}"]`);
                    // Insert les bloc html pour la modal et le menu à la fin du parent
                    parent.insertAdjacentHTML('beforeend', data.html_complet);

                    const modalElement = document.querySelector(`#new_menu`);
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    modalInstance.hide();
                }
            } else {
                show_message('Echec de la ajout');
            }
        });
}

document.querySelectorAll('.add_form').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const data = new FormData(form);
        add_menu(data.get('titre'), data.get('page_id'), data.get('parent_id'));
    });
});




