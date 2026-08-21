<?php
function set_flash(string $message, string $type = 'danger'): void
{
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function get_flash(): ?array
{
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']); // supprimé immédiatement après lecture, "flash" = usage unique
    return $flash;
}