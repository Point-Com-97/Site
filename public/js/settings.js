// Intercept le la soumission du formulaire pour ajouter un bloc
document.querySelectorAll('.settings_form').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        edit_style(form);
    });
});

// Mets jour le style du site via le formulaire et la methode POST
function edit_style(form) {
    const data = new FormData(form);
    const url = '/admin/settings/update.php';

    fetch(url, { method: 'POST', body: data })
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                show_message('Style modifié', 'success');
                // pour l'instant, recharge simple pour voir le résultat mis à jour
                location.reload();
            } else {
                show_message('Échec');
            }
        });
}
