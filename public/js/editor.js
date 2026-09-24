// Initialisation de l'éditeur de text riche Quill
const quill = new Quill('#quill-editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'font': [] }, { 'size': [] }],
            ['code', 'code-block'],
            ['blockquote', 'link'],
            [{ 'script': 'sub' }, { 'script': 'super' }],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'indent': '-1' }, { 'indent': '+1' }],
            [{ 'align': [] }],
            [{ 'direction': 'rtl' }],
            ['image', 'video'],
            ['clean']
        ]
    }
});

// Gestion de l'upload d'image via le bouton de la toolbar format ajax pour Quill
const toolbar = quill.getModule('toolbar');
toolbar.addHandler('image', function () {
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();

    input.onchange = function () {
        const file = input.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('media', file);
        formData.append('csrf_token', document.querySelector('[name="csrf_token"]').value);

        fetch('/admin/media/upload-ajax.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const range = quill.getSelection(true);
                    quill.insertEmbed(range.index, 'image', data.url);
                } else {
                    show_message('Échec de l\'upload de l\'image');
                }
            });
    };
});

// Supprimer un bloc via la méthode fetch et la méthode GET
function remove_items_bloc(id, csrf) {
    if (!confirm('Supprimer ce bloc définitivement ?')) {
        return;
    }
    fetch(`/admin/pages/delete.php?id=${id}&csrf_token=${csrf}`)
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
    document.getElementById('hidden-contenu').value = quill.root.innerHTML;
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

// Pré-remplir le formulaire d'édition avec les données du bloc sélectionné
function prefill_bloc(bouton) {
    const id = bouton.dataset.blocId;
    const type = bouton.dataset.blocType;
    const donnees = JSON.parse(bouton.dataset.blocDonnees);

    const select = document.getElementById('bloc_type');
    select.value = type;
    select.dispatchEvent(new Event('change'));
    document.querySelector('[name="classes_css"]').value = donnees.classes ?? '';

    switch (type) {
        case 'texte':
            quill.clipboard.dangerouslyPasteHTML(donnees.contenu);
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
            const nbColonnes = donnees.colonnes.length;
            const nbLignes = donnees.lignes.length;

            document.getElementById('nb_col').value = nbColonnes;
            document.getElementById('col_value').textContent = nbColonnes;
            document.getElementById('nb_row').value = nbLignes;
            document.getElementById('row_value').textContent = nbLignes;

            regenerer_champs('tableau-col-fields', nbColonnes, (i) => `
        <input type="text" name="col_${i}" class="form-control" placeholder="col_n°${i}">
    `);
            generer_lignes(nbLignes, nbColonnes);

            donnees.colonnes.forEach(function (col, index) {
                document.querySelector(`[name="col_${index + 1}"]`).value = col;
            });

            donnees.lignes.forEach(function (ligne, ligneIndex) {
                ligne.forEach(function (cellule, colIndex) {
                    document.querySelector(`[name="cell_${ligneIndex + 1}_${colIndex + 1}"]`).value = cellule;
                });
            });
            break;
    }

    // basculer le formulaire en mode édition
    const modal = document.querySelector('.add_form_bloc');
    modal.dataset.mode = 'update';
    modal.dataset.blocId = id;
}

// Intercept le la soumission du formulaire pour ajouter un bloc
document.querySelectorAll('.add_form_bloc').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        add_bloc(form);
    });
});



// Masquer ou afficher les champs en fonction du type de bloc sélectionné
const blocType = document.getElementById('bloc_type');
if (blocType) {
    blocType.addEventListener('change', function () {
        document.querySelectorAll('.champs-bloc').forEach(function (div) {
            div.style.display = (div.dataset.type === this.value) ? 'block' : 'none';
        }.bind(this));
    });
}

// Fonction pour régénérer dynamiquement les champs en fonction du nombre spécifié
function regenerer_champs(conteneurId, nombre, generateurHtml) {
    const conteneur = document.getElementById(conteneurId);
    let html = '';
    for (let i = 1; i <= nombre; i++) {
        html += generateurHtml(i);
    }
    conteneur.innerHTML = html;
}

function generer_lignes(nbLignes, nbColonnes) {
    let html = '';
    for (let i = 1; i <= nbLignes; i++) {
        html += `<div class="input-group mb-1">`;
        for (let j = 1; j <= nbColonnes; j++) {
            html += `<input type="text" name="cell_${i}_${j}" class="form-control" placeholder="L${i}C${j}">`;
        }
        html += `</div>`;
    }
    document.getElementById('tableau-row-fields').innerHTML = html;
}

// Mettre à jour le nombre de labels pour le graphique stats et générer dynamiquement les champs correspondants
const labelInput = document.getElementById('nb_label');
const labelOutput = document.getElementById('label_value');

if (labelInput && labelOutput) {
    labelOutput.textContent = labelInput.value;

    labelInput.addEventListener('input', function () {
        labelOutput.textContent = this.value;
    });
}

labelInput.addEventListener('input', function () {
    labelOutput.textContent = this.value;
    regenerer_champs('stats-fields', this.value, (i) => `
        <div class="input-group">
            <span class="input-group-text">Données</span>
            <input type="text" name="labels_${i}" class="form-control" placeholder="Nom du champs">
            <input type="text" name="data_${i}" class="form-control" placeholder="Valeur">
        </div>
    `);
});

// Mettre à jour le nombre de colonnes et lignes pour le tableau et générer dynamiquement les champs correspondants
const colInput = document.getElementById('nb_col');
const colOutput = document.getElementById('col_value');
const rowInput = document.getElementById('nb_row');
const rowOutput = document.getElementById('row_value');

function mettre_a_jour_colonnes() {
    colOutput.textContent = colInput.value;
    regenerer_champs('tableau-col-fields', colInput.value, (i) => `
        <input type="text" name="col_${i}" class="form-control" placeholder="col_n°${i}">
    `);
}

function mettre_a_jour_lignes() {
    rowOutput.textContent = rowInput.value;
    generer_lignes(parseInt(rowInput.value), parseInt(colInput.value));
}

if (colInput && rowInput) {
    colInput.addEventListener('input', function () {
        mettre_a_jour_colonnes();
        mettre_a_jour_lignes(); // le nombre de colonnes a changé, les lignes doivent suivre
    });

    rowInput.addEventListener('input', mettre_a_jour_lignes);

    mettre_a_jour_colonnes();
    mettre_a_jour_lignes();
}


// Réinitialiser le formulaire lors de la fermeture de la modal
const newBlocModal = document.getElementById('new_bloc');
if (newBlocModal) {
    newBlocModal.addEventListener('hidden.bs.modal', function () {
        const form = document.querySelector('.add_form_bloc');
        form.reset();
        document.getElementById('bloc_type').dispatchEvent(new Event('change'));
        form.dataset.mode = 'create';
        quill.setText('');
        delete form.dataset.blocId;

        labelInput.value = 3;
        mettre_a_jour_colonnes();
        mettre_a_jour_lignes();
        regenerer_champs('stats-fields', 3, (i) => `
        <div class="input-group">
            <span class="input-group-text">Données</span>
            <input type="text" name="labels_${i}" class="form-control" placeholder="Nom du champs">
            <input type="text" name="data_${i}" class="form-control" placeholder="Valeur">
        </div>
    `);

    });
}


const blocList = document.getElementById('bloc-list');
if (blocList) {
    activer_drag_drop(blocList, '.container[draggable="true"]', 'bloc');
}