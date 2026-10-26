<?php
/**
 * Classe utilitaire pour la validation securisee des uploads.
 */

declare(strict_types=1);

class UploadSecurise
{
    private const TYPES_AUTORISES = [
        'jpg'  => ['image/jpeg', 'image'],
        'jpeg' => ['image/jpeg', 'image'],
        'png'  => ['image/png', 'image'],
        'webp' => ['image/webp', 'image'],
        'gif'  => ['image/gif', 'image'],
        'pdf'  => ['application/pdf', 'pdf'],
    ];

    private const TAILLE_MAX = 10485760; // 10 Mo

    public static function valider(array $file): array
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Erreur lors de l\'upload.'];
        }

        if ($file['size'] > self::TAILLE_MAX) {
            return ['success' => false, 'message' => 'Le fichier depasse la limite de 10 Mo.'];
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            return ['success' => false, 'message' => 'Fichier invalide.'];
        }

        $nomOriginal = basename($file['name']);
        $extension = strtolower(pathinfo($nomOriginal, PATHINFO_EXTENSION));

        if (!isset(self::TYPES_AUTORISES[$extension])) {
            return ['success' => false, 'message' => 'Extension non autorisee.'];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo === false) {
            return ['success' => false, 'message' => 'Impossible de verifier le type de fichier.'];
        }
        $mimeDetecte = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        [$mimeAttendu, $categorie] = self::TYPES_AUTORISES[$extension];

        if ($mimeDetecte !== $mimeAttendu) {
            return ['success' => false, 'message' => 'Le type de fichier ne correspond pas a l\'extension.'];
        }

        if ($categorie === 'image') {
            $imageInfo = @getimagesize($file['tmp_name']);
            if ($imageInfo === false) {
                return ['success' => false, 'message' => 'Le fichier image est corrompu.'];
            }
            if (($imageInfo['mime'] ?? '') !== $mimeAttendu) {
                return ['success' => false, 'message' => 'Le type de l\'image ne correspond pas.'];
            }
        }

        if ($categorie === 'pdf') {
            $handle = fopen($file['tmp_name'], 'rb');
            if ($handle === false) {
                return ['success' => false, 'message' => 'Impossible de lire le fichier.'];
            }
            $header = fread($handle, 5);
            fclose($handle);
            if ($header !== '%PDF-') {
                return ['success' => false, 'message' => 'Le fichier PDF est invalide.'];
            }
        }

        return [
            'success'      => true,
            'message'      => 'Fichier valide.',
            'extension'    => $extension,
            'type'         => $categorie,
            'nom_original' => $nomOriginal,
        ];
    }

    public static function nomUnique(string $extension): string
    {
        return bin2hex(random_bytes(16)) . '.' . $extension;
    }
}
