<?php
function getConnexion() {
    $host = 'localhost';
    $dbname = 'ton_nom_de_base';
    $user = 'ton_user';
    $password = 'ton_mot_de_passe';
    
    return new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
}