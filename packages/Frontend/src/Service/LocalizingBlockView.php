<?php

namespace Solidarity\Frontend\Service;

use Skeletor\ContentEditor\Contracts\BlockViewFilterInterface;
use Skeletor\ContentEditor\Contracts\BlockViewInterface;

/**
 * Decorates the block View so admin-entered internal URLs in CMS block data are
 * localized for the active locale before rendering. Block templates echo their link
 * fields raw (buttonLink, linkUrl, buttonUrl, ...); this walks the block data and runs
 * each such field through Locale::localizeUrl, so block links follow the active
 * locale's slugs exactly like the menus and footer do.
 *
 * Internal relative links are also normalized to a leading slash so they can't compound
 * against the current path (e.g. a relative `doniraj` on `/en/x` resolving to
 * `/en/en/doniraj`). External, protocol-relative and anchor links are left untouched.
 */
class LocalizingBlockView implements BlockViewInterface
{
    /** Keys (case-insensitive) whose value is a link to localize: *url, *link, *href. */
    private const URL_KEY = '/(?:url|link|href)$/i';

    public function __construct(
        private BlockViewInterface $inner,
        private Locale $locale,
    ) {
    }

    public function getView(array $data = []): string
    {
        return $this->inner->getView($this->processUrls($data));
    }

    public function registerViewFilter(string $name, BlockViewFilterInterface $blockViewFilter): void
    {
        $this->inner->registerViewFilter($name, $blockViewFilter);
    }

    /**
     * Recursively normalize every URL-ish field to a leading slash (so relative links
     * can't compound against the current path) and, on non-default locales, run it
     * through Locale::localizeUrl to swap in the active locale's slug.
     */
    private function processUrls(array $data): array
    {
        $localize = !$this->locale->isDefault();
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->processUrls($value);
            } elseif (is_string($key) && is_string($value) && preg_match(self::URL_KEY, $key)) {
                $url = Locale::absolutePath($value);
                $data[$key] = $localize ? $this->locale->localizeUrl($url) : $url;
            }
        }
        return $data;
    }
}
