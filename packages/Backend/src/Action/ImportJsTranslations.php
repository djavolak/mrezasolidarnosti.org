<?php

namespace Solidarity\Backend\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Scans a JS asset tree for `translate('...')` calls (the skeletorjs Translator's
 * collection function, e.g. `Translator.translate('Show Password')`) and writes an
 * idempotent SQL file that inserts each string into the `translation` table with an
 * EMPTY translatedString for the side's target language, skipping strings already
 * present. The strings are then filled in the admin and picked up by exportTranslations.
 *
 * Run manually (the 2nd arg selects the side):
 *   php public/cli.php importJsTranslations backend
 *   php public/cli.php importJsTranslations frontend
 *
 * @TODO Generalise into the Skeletor Translator package (dirs/languages via config)
 *       so every project can collect its JS strings the same way.
 */
class ImportJsTranslations
{
    // Both sides use English source strings translated into Serbian ('sr'), so the JS file
    // stays keyed by English: "English": {"sr": "Serbian"}. The switch only picks the dir.
    /** side => [ js dir relative to APP_PATH, target language code, language display name ] */
    private const SIDES = [
        'backend'  => ['dir' => '/public/assets/backend/js',  'lang' => 'sr', 'name' => 'Serbian'],
        'frontend' => ['dir' => '/public/assets/frontend/js', 'lang' => 'sr', 'name' => 'Serbian'],
    ];

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = (array) $request->getAttribute('params', []);
        $side = null;
        foreach (array_keys(self::SIDES) as $candidate) {
            if (in_array($candidate, $params, true)) {
                $side = $candidate;
                break;
            }
        }
        if ($side === null) {
            echo 'Usage: php public/cli.php importJsTranslations <backend|frontend>' . PHP_EOL;
            return $response;
        }

        $conf = self::SIDES[$side];
        $dir = APP_PATH . $conf['dir'];
        if (!is_dir($dir)) {
            echo "Directory not found: {$dir}" . PHP_EOL;
            return $response;
        }

        [$strings, $fileCount] = $this->scan($dir);
        echo sprintf('Scanned %d .js file(s) under %s', $fileCount, $conf['dir']) . PHP_EOL;
        echo sprintf("Found %d unique translate() string(s) for language '%s'", count($strings), $conf['lang']) . PHP_EOL;

        $out = DATA_PATH . '/js_translations_' . $side . '.sql';
        file_put_contents($out, $this->buildSql($strings, $conf['lang'], $conf['name']));

        echo "Wrote SQL -> {$out}" . PHP_EOL;
        echo "Review it, then import it, then run: php public/cli.php exportTranslations run" . PHP_EOL;

        return $response;
    }

    /**
     * Recursively collect the unique first-argument string literals of every
     * `translate('...')` / `.translate("...")` call in the .js files under $dir.
     *
     * @return array{0: list<string>, 1: int} [sorted unique strings, files scanned]
     */
    private function scan(string $dir): array
    {
        // translate( <'…' | "…" | `…`> ...) — first string literal, escapes honoured.
        $pattern = "~\\btranslate\\s*\\(\\s*(['\"`])((?:\\\\.|.)*?)\\1~s";
        $found = [];
        $files = 0;

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
        );
        foreach ($iterator as $file) {
            if (strtolower($file->getExtension()) !== 'js') {
                continue;
            }
            $files++;
            $code = file_get_contents($file->getPathname());
            if ($code === false || preg_match_all($pattern, $code, $matches, PREG_SET_ORDER) === false) {
                continue;
            }
            foreach ($matches as $match) {
                $raw = $match[2];
                if (str_contains($raw, '${')) {
                    continue; // dynamic template literal — not a static key
                }
                $string = stripcslashes($raw);
                if ($string !== '') {
                    $found[$string] = true;
                }
            }
        }

        $strings = array_keys($found);
        sort($strings);

        return [$strings, $files];
    }

    /** @param list<string> $strings */
    private function buildSql(array $strings, string $lang, string $name): string
    {
        $esc = static fn (string $s): string => str_replace(['\\', "'"], ['\\\\', "''"], $s);
        $l = $esc($lang);

        $lines = [
            'SET NAMES utf8mb4;',
            '',
            "-- JS strings collected from translate() calls. Target language: '{$l}'.",
            '-- translatedString is empty on purpose: fill them in the admin Translator,',
            '-- then run `php public/cli.php exportTranslations run` to regenerate the JS file.',
            '',
            '-- Seed the target language row if missing.',
            'INSERT INTO `language` (`name`, `code`, `createdAt`, `updatedAt`)',
            "SELECT * FROM (SELECT '" . $esc($name) . "' AS name, '{$l}' AS code, NOW() AS createdAt, NOW() AS updatedAt) AS tmp",
            "WHERE NOT EXISTS (SELECT 1 FROM `language` WHERE `code` = '{$l}');",
            '',
        ];

        foreach ($strings as $string) {
            $o = $esc($string);
            $lines[] = 'INSERT INTO `translation` (`originalString`, `translatedString`, `languageId`, `createdAt`, `updatedAt`)';
            $lines[] = "SELECT '{$o}', '', l.`id`, NOW(), NOW() FROM `language` l";
            $lines[] = "WHERE l.`code` = '{$l}' AND NOT EXISTS (";
            $lines[] = "  SELECT 1 FROM `translation` t WHERE t.`originalString` = '{$o}' AND t.`languageId` = l.`id`";
            $lines[] = ');';
        }

        return implode("\n", $lines) . "\n";
    }
}
