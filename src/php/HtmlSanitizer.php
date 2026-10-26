<?php
/**
 * Sanitiseur HTML minimal pour le contenu de l'editeur Quill.
 */

declare(strict_types=1);

class HtmlSanitizer
{
    private const BALISES_AUTORISEES = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'del', 'ins',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'ul', 'ol', 'li',
        'a', 'img',
        'blockquote', 'cite',
        'code', 'pre',
        'hr',
        'table', 'thead', 'tbody', 'tr', 'th', 'td',
        'span', 'div',
        'sub', 'sup',
    ];

    private const ATTRIBUTS_AUTORISES = [
        'global' => ['class', 'style'],
        'a'      => ['href', 'title', 'target', 'rel'],
        'img'    => ['src', 'alt', 'width', 'height', 'loading'],
    ];

    private const SCHEMAS_AUTORISES = ['http', 'https', 'mailto'];

    public static function nettoyer(string $html): string
    {
        if (trim($html) === '') {
            return '';
        }
        if (class_exists('DOMDocument')) {
            return self::nettoyerAvecDom($html);
        }
        return self::nettoyerAvecRegex($html);
    }

    private static function nettoyerAvecDom(string $html): string
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="UTF-8">' . $html,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        self::nettoyerNoeud($dom);
        $result = '';
        foreach ($dom->childNodes as $node) {
            $result .= $dom->saveHTML($node);
        }
        $result = preg_replace('/^<\?xml[^>]*>/', '', $result);
        return trim($result);
    }

    private static function nettoyerNoeud(DOMNode $node): void
    {
        if ($node->hasChildNodes()) {
            $children = [];
            foreach ($node->childNodes as $child) {
                $children[] = $child;
            }
            foreach ($children as $child) {
                self::nettoyerNoeudEnfant($node, $child);
            }
        }
    }

    private static function nettoyerNoeudEnfant(DOMNode $parent, DOMNode $child): void
    {
        if ($child instanceof DOMComment) {
            $parent->removeChild($child);
            return;
        }
        if ($child instanceof DOMElement) {
            $tag = strtolower($child->tagName);
            $balisesInterdites = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'textarea', 'select', 'link', 'meta', 'base'];
            if (in_array($tag, $balisesInterdites, true)) {
                $parent->removeChild($child);
                return;
            }
            if (!in_array($tag, self::BALISES_AUTORISEES, true)) {
                while ($child->firstChild) {
                    $parent->insertBefore($child->firstChild, $child);
                }
                $parent->removeChild($child);
                return;
            }
            self::nettoyerAttributs($child);
        }
        self::nettoyerNoeud($child);
    }

    private static function nettoyerAttributs(DOMElement $element): void
    {
        $tag = strtolower($element->tagName);
        $attributsAutorises = array_merge(
            self::ATTRIBUTS_AUTORISES['global'],
            self::ATTRIBUTS_AUTORISES[$tag] ?? []
        );
        $attributs = [];
        foreach ($element->attributes as $attr) {
            $attributs[] = $attr;
        }
        foreach ($attributs as $attr) {
            $nom = strtolower($attr->name);
            $valeur = $attr->value;
            if (str_starts_with($nom, 'on')) {
                $element->removeAttribute($attr->name);
                continue;
            }
            if (!in_array($nom, $attributsAutorises, true)) {
                $element->removeAttribute($attr->name);
                continue;
            }
            if (in_array($nom, ['href', 'src'], true)) {
                $valeurNettoyee = self::nettoyerUrl($valeur);
                if ($valeurNettoyee === null) {
                    $element->removeAttribute($attr->name);
                    continue;
                }
                $element->setAttribute($attr->name, $valeurNettoyee);
            }
            if ($nom === 'style') {
                $element->setAttribute('style', self::nettoyerStyle($valeur));
            }
        }
    }

    private static function nettoyerUrl(string $url): ?string
    {
        $url = trim($url);
        $urlLower = strtolower($url);
        if (str_starts_with($urlLower, 'javascript:') ||
            str_starts_with($urlLower, 'data:') ||
            str_starts_with($urlLower, 'vbscript:')) {
            return null;
        }
        if (str_starts_with($url, '/') || str_starts_with($url, '#') ||
            str_starts_with($url, './') || str_starts_with($url, '../')) {
            return $url;
        }
        $parsed = parse_url($url);
        if ($parsed === false) {
            return null;
        }
        $scheme = strtolower($parsed['scheme'] ?? '');
        if (!in_array($scheme, self::SCHEMAS_AUTORISES, true)) {
            return null;
        }
        return $url;
    }

    private static function nettoyerStyle(string $style): string
    {
        $style = preg_replace('/expression\s*\(/i', '', $style);
        $style = preg_replace('/javascript:/i', '', $style);
        $style = preg_replace('/vbscript:/i', '', $style);
        $style = preg_replace('/-moz-binding/i', '', $style);
        $style = preg_replace('/behavior\s*:/i', '', $style);
        return trim($style);
    }

    private static function nettoyerAvecRegex(string $html): string
    {
        $html = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $html);
        $html = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $html);
        $html = preg_replace('/<iframe\b[^>]*>.*?<\/iframe>/is', '', $html);
        $html = preg_replace('/<object\b[^>]*>.*?<\/object>/is', '', $html);
        $html = preg_replace('/<embed\b[^>]*>/is', '', $html);
        $html = preg_replace('/\s+on\w+\s*=\s*["\'][^"\']*["\']/i', '', $html);
        $html = preg_replace('/\s+on\w+\s*=\s*[^\s>]+/i', '', $html);
        $html = preg_replace('/(href|src)\s*=\s*["\']\s*(javascript|data|vbscript):[^"\']*["\']/i', '$1="#"', $html);
        return $html;
    }
}
