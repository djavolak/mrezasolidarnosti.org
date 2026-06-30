<?php
namespace Solidarity\Transliterator\Service;

use Turanjanin\SerbianTransliterator\Transliterator as SerbianTransliterator;

/**
 * Thin wrapper over turanjanin/serbian-transliterator for Serbian Cyrillic -> Latin
 * conversion. Delegates to the library so the full alphabet (upper- and lower-case,
 * digraphs, foreign-word handling) is covered rather than a partial hand-rolled map.
 */
class Transliterator
{
    public function transliterate($text): string
    {
        if (empty($text)) {
            return (string) $text;
        }

        return SerbianTransliterator::toLatin((string) $text);
    }

    public function isCyrillic($text): bool
    {
        return (bool) preg_match('/\p{Cyrillic}/u', (string) $text);
    }
}
