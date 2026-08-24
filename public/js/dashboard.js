function remove_menu(id) {
    if (!confirm('Supprimer ce menu définitivement ?')) {
        return;
    }

    fetch(`/admin/endpoint/delete.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                show_message('Suppression effectuée.', 'success');
                const menu = document.querySelector(`[data-id="${id}"]`);  // querySelector peut bloquer l'éxecution des fonctions
                menu.remove();
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

document.querySelectorAll('.edit_form').forEach(function(form) {
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const data = new FormData(form);
        edit_menu(data.get('id'), data.get('menu_titre'));
    });
});




