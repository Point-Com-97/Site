<?php
session_start([
    'cookie_lifetime' => 7200,
]);

if (empty($_SESSION['admin_id'])) {
    
header('Location: /admin/login.php');
    exit;
}
