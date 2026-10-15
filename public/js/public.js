// 1. Apparition au défilement
const reveals = document.querySelectorAll('.reveal');
if ('IntersectionObserver' in window && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
    const obs = new IntersectionObserver((entries) => {
        entries.forEach((en) => {
            if (en.isIntersecting) { en.target.classList.add('visible'); obs.unobserve(en.target); }
        });
    }, { threshold: 0.15 });
    reveals.forEach((el) => obs.observe(el));
} else {
    reveals.forEach((el) => el.classList.add('visible')); // pas d'animation : tout visible
}

// 2. Vidéo : l'iframe n'est créée qu'au clic
document.querySelectorAll('.video-clic').forEach((box) => {
    box.querySelector('.video-play').addEventListener('click', () => {
        const f = document.createElement('iframe');
        const url = new URL(box.dataset.src);
        url.searchParams.set('autoplay', '1');
        f.src = url.toString();
        f.title = box.dataset.titre;
        f.allow = 'autoplay; encrypted-media; picture-in-picture';
        f.allowFullscreen = true;
        box.replaceChildren(f);
    });
});

// 3. Graphiques : Chart.js n'est chargé que s'il y en a
const charts = document.querySelectorAll('.js-chart');
if (charts.length) {
    const s = document.createElement('script');
    s.src = '/assets/vendor/chart.js/dist/chart.umd.min.js';
    s.onload = () => charts.forEach((c) => new Chart(c, JSON.parse(c.dataset.chart)));
    document.head.appendChild(s);
}
