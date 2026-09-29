const searchInput = document.getElementById('search-input');
const searchResults = document.getElementById('search-results');
let debounceTimer;

searchInput.addEventListener('input', function () {
    clearTimeout(debounceTimer);
    const terme = this.value.trim();

    if (terme.length < 2) {
        searchResults.style.display = 'none';
        return;
    }

    debounceTimer = setTimeout(() => {
        fetch(`/search.php?q=${encodeURIComponent(terme)}`)
            .then(response => response.json())
            .then(pages => {
                searchResults.innerHTML = '';
                if (pages.length === 0) {
                    searchResults.innerHTML = '<li class="list-group-item text-muted">Aucun résultat</li>';
                } else {
                    pages.forEach(p => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item';
                        const a = document.createElement('a');
                        a.href = `/${p.slug}`;
                        a.textContent = p.titre;
                        li.appendChild(a);
                        searchResults.appendChild(li);
                    });
                }
                searchResults.style.display = 'block';
            });
    }, 300);
});

document.addEventListener('click', function (e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.style.display = 'none';
    }
});