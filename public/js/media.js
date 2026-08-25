function charger_medias(critere) {
    fetch(`/admin/media/endpoint/sort.php?tri=${critere}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('media-grid').innerHTML = data.html;
        })
        .catch(error => {
            console.error('Erreur lors du tri :', error);
            show_message('Erreur lors du tri.');
        });
}

document.querySelectorAll('.dropdown-item').forEach(function(lien) {
    lien.addEventListener('click', function(event) {
        event.preventDefault();
        const critere = this.textContent.trim() === 'Date' ? 'created_at' : 'titre'; // critere = Date ou created_at si non par défaut = titre
        charger_medias(critere);
    });
});

function remove_media(id) {
    if (!confirm('Supprimer ce média définitivement ?')) {
        return;
    }

    fetch(`/admin/media/endpoint/delete.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
             const media = document.querySelector(`[data-id="${id}"]`);
               media.remove();
            } else {
                show_message('La suppression a échoué.');
            }
        });
}



