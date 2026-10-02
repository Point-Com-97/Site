<?php
require_once __DIR__ . '/../src/php/Page.php';

header('Content-Type: application/xml; charset=utf-8');

$page = new Page();
$pages = $page->getByMenuOn();

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

echo '<url><loc>https://www.pointcom-guyane.fr/</loc><priority>1.0</priority></url>' . "\n";

foreach ($pages as $p) {
    if ((int) $p['visible'] === 1 && $p['slug'] !== 'accueil') {
        $loc = htmlspecialchars('https://www.pointcom-guyane.fr/' . $p['slug']);
        echo "<url><loc>{$loc}</loc><priority>0.8</priority></url>\n";
    }
}

echo '</urlset>';