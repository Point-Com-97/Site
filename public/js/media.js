
function afficher_medias(medias) {
    const grille = document.getElementById('media-grid');
    grille.innerHTML = ''; // vide la grille avant de la reconstruire

    medias.forEach(media => {

        // Création des éléments du div.col        
        const col = document.createElement('div');
        col.classList.add('col');

        // Création des éléments du div.card   
        const card = document.createElement('div');
        card.classList.add('card');

        //création des éléments du card.img
        const img = document.createElement('img');
        img.src = media.type === 'image' ? media.url : '/assets/image/pdf.png';
        img.classList.add('card-img-top', 'img-thumbnail');
        img.alt = media.titre;

        // Création des éléments du div.card-body
        const card_body = document.createElement('div');
        card_body.classList.add('card-body');

        // Création des éléments du h5.card-title
        const card_title = document.createElement('h5');
        card_title.classList.add('card-title');
        card_title.textContent = media.titre;

        // Création des éléments du p.card-text
        const card_text = document.createElement('p');
        card_text.classList.add('card-text');
        card_text.textContent = `Date de création : ${media.created_at}`;
        
        // Création des éléments du div.btn-group    
        const btn_group = document.createElement('div');
        btn_group.classList.add('btn-group');

        // Création des éléments i bouton suppression
        const ico_delete = document.createElement('i');
        ico_delete .classList.add('bi');
        ico_delete .classList.add('bi-trash3-fill');

        // Création des éléments du bouton suppression
        const btn_delete = document.createElement('button');
        btn_delete.classList.add('btn');
        btn_delete.classList.add('btn-danger');
        // Ajouter une fonction onclick sur une balise et désactivé son déclement lors de la création
        btn_delete.onclick = function() {remove_media(media.id)}; 
    

        // Assemblage des éléments
        btn_delete.appendChild(ico_delete);

        btn_group.appendChild(btn_delete);

        card_body.appendChild(card_title);

        card_body.appendChild(card_text);

        card.appendChild(img);

        card.appendChild(card_body);

        card.appendChild(btn_group);

        col.appendChild(card);

        // Ajout du div.col à la grille
        grille.appendChild(col);
    });
}

function charger_medias(critere) {
    fetch(`/admin/media/endpoint/sort.php?tri=${critere}`)
        .then(response => response.json())
        .then(medias => {
            afficher_medias(medias);
        })
        .catch(error => {
            console.error('Erreur lors du tri :', error);
            show_message('Erreur lors du tri :');
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



