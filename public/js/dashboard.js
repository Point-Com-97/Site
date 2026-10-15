// Supprimer d'une page via le formulaire et la methode POST
function remove_items(id, csrf) {
    if (!confirm('Supprimer cette page définitivement ?')) {
        return;
    }
    const formData = new FormData();
    formData.append('id', id);
    formData.append('csrf_token', csrf)

    try {
        fetch(`/admin/endpoint/delete.php`, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    show_message('Suppression effectuée.', 'success');

                    const menu = document.getElementById(`Page_${id}`);
                    menu.remove();

                } else {
                    show_message('La suppression a échoué.');
                }
            })
            .catch(error => {
                console.error('Erreur lors de la suppression :', error);
                show_message('Une erreur est survenue lors de la suppression.', 'danger');

            });
    } catch (error) {
        console.error('Erreur lors de la suppression :', error);
        show_message('Une erreur est survenue lors de la suppression.', 'danger');
    }
}

// Toggle la visibilité d'une page via le formulaire et la methode POST
function toggle_visible(id, csrf) {

    const formData = new FormData();
    formData.append('id', id);
    formData.append('csrf_token', csrf)

    try {
        fetch(`/admin/endpoint/toggle.php`, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const icone = document.getElementById(`visible${id}`);
                    const etait_visible = icone.classList.contains('text-success');

                    icone.classList.toggle('text-success'); // ajoute la classe si elle est absente, ou la retire si elle est présente

                    show_message(etait_visible ? 'Page passée hors-ligne' : 'Page passée en ligne', 'success');
                } else {
                    show_message('Echec de la tentative');
                }
            })
            .catch(error => {
                console.error('Erreur lors du changement de statut :', error);
                show_message('Une erreur est survenue lors du changement de statut ', 'danger');

            });
    } catch (error) {
        console.error('Erreur lors du changement de la visibilité :', error);
        show_message('Une erreur est survenue lors du changement de la visibilité.', 'danger');
    }
}

// Modifier d'une page via le formulaire et la methode POST
function edit(id, menu_slug, titre, meta_description, csrf) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('menu_slug', menu_slug);
    formData.append('titre', titre);
    formData.append('meta_description', meta_description);
    formData.append('csrf_token', csrf);

    try {
        fetch(`/admin/endpoint/update.php`, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    show_message('Mise à jour réussie', 'success');

                    const title = document.getElementById(`Page_label_${id}`);
                    title.textContent = titre;

                    const modalElement = document.querySelector(`#editModalPage${id}`);
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    modalInstance.hide();
                    setTimeout(() => location.reload(), 800);
                } else {
                    show_message('Echec de la modification');
                }
            })
            .catch(error => {
                console.error('Erreur lors de la mise à jour :', error);
                show_message('Une erreur est survenue lors de la mise à jour.', 'danger');

            });
    } catch (error) {
        console.error('Erreur lors de la mise à jour :', error);
        show_message('Une erreur est survenue lors de la mise à jour.', 'danger');
    }
}

// Ajouter une page via le formulaire et la methode POST
function add(titre, menu_slug, meta_description, csrf) {

    const formData = new FormData();
    formData.append('titre', titre);
    formData.append('menu_slug', menu_slug);
    formData.append('meta_description', meta_description);
    formData.append('csrf_token', csrf);

    try {
        fetch(`/admin/endpoint/create.php`, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {

                    show_message('Element ajouter', 'success');

                    const modalElement = document.querySelector(`#new_page`);
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);

                    modalInstance.hide();

                    setTimeout(() => location.reload(), 800);
                } else {
                    show_message('Echec de la ajout');
                }
            })
            .catch(error => {
                console.error('Erreur lors de l\'ajout :', error);
                show_message('Une erreur est survenue lors de l\'ajout.', 'danger');
            });
    } catch (error) {
        console.error('Erreur lors de l\'ajout :', error);
        show_message('Une erreur est survenue lors de l\'ajout.', 'danger');
    }
}

// QuerySelector Gestion des formulaires d'édition et d'ajout

document.querySelectorAll('.edit_form').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const data = new FormData(form);
        edit(data.get('id'), data.get('menu_slug'), data.get('titre'), data.get('meta_description'), data.get('csrf_token'));
    });
});


document.querySelectorAll('.add_form_page').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const data = new FormData(form);
        add(data.get('titre'), data.get('menu_slug'), data.get('meta_description'), data.get('csrf_token'));
    });
});
