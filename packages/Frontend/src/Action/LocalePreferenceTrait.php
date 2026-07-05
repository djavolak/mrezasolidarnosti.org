<?php

namespace Solidarity\Frontend\Action;

use GuzzleHttp\Psr7\Response;

/**
 * Session-backed locale preference.
 *
 * The language switcher records an explicit choice via `?setLocale=<code>`; a later
 * mistaken landing on a default-locale URL is then redirected to the preferred locale —
 * but only when that page actually has a translation (otherwise it stays put).
 *
 * The redirect only ever fires on default-locale URLs and always targets a non-default
 * one, so it cannot loop; and because the preference is set only by an explicit switch,
 * choosing Serbian in the switcher (pref = sr) lets the user browse Serbian freely.
 *
 * Requires the using action to expose $this->locale (Locale), $this->session (Session)
 * and $this->pageRepository (PageRepository), plus Html::redirect().
 */
trait LocalePreferenceTrait
{
    /**
     * @param ?string $currentSlug the requested page slug, or null for the homepage
     * @return ?Response a 302 honoring an explicit switch or the preferred locale, else null
     */
    protected function resolveLocalePreference(?string $currentSlug): ?Response
    {
        $default = $this->locale->default();

        // 1) Explicit switch via the language switcher: record it, then strip the marker
        //    by bouncing to the clean URL of the page we're already on.
        $setLocale = $_GET['setLocale'] ?? null;
        if (is_string($setLocale) && in_array($setLocale, $this->locale->available(), true)) {
            $this->session->setPreferredLocale($setLocale);
            return $this->redirect($this->locale->localize($this->locale->basePath(), $this->locale->current()));
        }

        // 2) Mistaken landing on the default locale while preferring another one.
        if (!$this->locale->isDefault()) {
            return null;
        }
        $preferred = $this->session->getPreferredLocale();
        if (!$preferred || $preferred === $default) {
            return null;
        }
        if ($currentSlug === null) { // homepage → localized root
            return $this->redirect($this->locale->localize('/', $preferred));
        }
        $translated = $this->pageRepository->findTranslatedSlug($currentSlug, $default, $preferred);
        if ($translated !== null) {
            return $this->redirect($this->locale->localize('/' . $translated, $preferred));
        }
        return null;
    }
}
