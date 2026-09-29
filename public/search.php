<?php
require_once __DIR__ . '/../src/php/Page.php';

header('Content-Type: application/json');

$terme = $_GET['q'] ?? '';

if (strlen(trim($terme)) < 2) {
    echo json_encode([]);
    exit;
}

$page = new Page();
echo json_encode($page->search($terme));