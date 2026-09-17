// Supprimer un bloc via la méthode fetch et la méthode GET
function remove_items_bloc(id) {
    if (!confirm('Supprimer ce bloc définitivement ?')) {
        return;
    }
    fetch(`/admin/pages/delete.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                show_message('Suppression effectuée.', 'success');

                    const bloc = document.getElementById(`Bloc_${id}`);
                    bloc.remove();

            } else {
                show_message('La suppression a échoué.');
            }
        });
}

// Ajouter un bloc via le formulaire et la methode POST
function add_bloc(form) {
    const data = new FormData(form);
    const mode = form.dataset.mode || 'create';
    const url = mode === 'update'
        ? `/admin/pages/update.php`
        : '/admin/pages/create.php';

    if (mode === 'update') {
        data.append('id', form.dataset.blocId);
    }

    fetch(url, { method: 'POST', body: data })
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                show_message(mode === 'update' ? 'Bloc modifié' : 'Bloc ajouté', 'success');
                setTimeout(() => location.reload(), 800);
            } else {
                show_message('Échec');
            }
        });
}

// Intercept le la soumission du formulaire pour ajouter un bloc
document.querySelectorAll('.add_form_bloc').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        add_bloc(form);
    });
});

// Réinitialiser le formulaire lors de la fermeture de la modal
const newBlocModal = document.getElementById('new_bloc');
if (newBlocModal) {
    newBlocModal.addEventListener('hidden.bs.modal', function() {
        const form = document.querySelector('.add_form_bloc');
        form.reset();
        form.dataset.mode = 'create';
        delete form.dataset.blocId;
    });
}

// Masquer ou afficher les champs en fonction du type de bloc sélectionné
const blocType = document.getElementById('bloc_type');
if (blocType) {
    blocType.addEventListener('change', function () {
        document.querySelectorAll('.champs-bloc').forEach(function (div) {
        div.style.display = (div.dataset.type === this.value) ? 'block' : 'none';
    }.bind(this));
});
}
// Pré-remplir le formulaire d'édition avec les données du bloc sélectionné
function prefill_bloc(bouton) {
    const id = bouton.dataset.blocId;
    const type = bouton.dataset.blocType;
    const donnees = JSON.parse(bouton.dataset.blocDonnees);

    const select = document.getElementById('bloc_type');
    select.value = type;
    select.dispatchEvent(new Event('change'));

    switch (type) {
        case 'texte':
            document.querySelector('[name="contenu"]').value = donnees.contenu;
            break;
        case 'video':
            document.querySelector('[name="url"]').value = donnees.url;
            document.querySelector('[name="legende"]').value = donnees.legende;
            break;
        case 'image':
            document.querySelector('[name="media_id"]').value = donnees.media_id;
            document.querySelector('[name="legende"]').value = donnees.legende;
            break;
        case 'stats':
            donnees.data.labels.forEach(function (label, index) {
                document.querySelector(`[name="labels_${index + 1}"]`).value = label;
                document.querySelector(`[name="data_${index + 1}"]`).value = donnees.data.datasets[0].data[index];
            });
            break;
        case 'tableau':
            donnees.colonnes.forEach(function (col, colIndex) {
                document.querySelector(`[name="col_${colIndex + 1}"]`).value = col;
            });
            donnees.lignes.forEach(function (row, rowIndex) {
                document.querySelector(`[name="row_${rowIndex + 1}"]`).value = row;
            });
            break;
    }

    // basculer le formulaire en mode édition
    const modal = document.querySelector('.add_form_bloc');
    modal.dataset.mode = 'update';
    modal.dataset.blocId = id;
}


// Mettre à jour les valeurs affichées pour le nombre de labels, colonnes et lignes
const labelInput = document.getElementById('nb_label');
const labelOutput = document.getElementById('label_value');

if (labelInput && labelOutput) {
labelOutput.textContent = labelInput.value;

labelInput.addEventListener('input', function () {
    labelOutput.textContent = this.value;
});
}

const colInput = document.getElementById('nb_col');
const colOutput = document.getElementById('col_value');

if (colInput && colOutput) {
colOutput.textContent = colInput.value;

colInput.addEventListener('input', function () {
    colOutput.textContent = this.value;
});
}


const rowInput = document.getElementById('nb_row');
const rowOutput = document.getElementById('row_value');

if (rowInput && rowOutput) {
rowOutput.textContent = rowInput.value;

rowInput.addEventListener('input', function () {
    rowOutput.textContent = this.value;
});
}

const blocList = document.getElementById('bloc-list');
if (blocList) {
    activer_drag_drop(blocList, '.container[draggable="true"]', 'bloc');
}