function remove_menu(id) {
    if (!confirm('Supprimer ce menu définitivement ?')) {
        return;
    }

    fetch(`/admin/endpoint/delete.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
             const menu = document.querySelector(`[data-id="${id}"]`);
               menu.remove();
            } else {
                show_message('La suppression a échoué.');
            }
        });
}



