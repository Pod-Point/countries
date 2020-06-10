<?php

namespace PodPoint\I18n;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Translation\Translator;

class Language
{
    const ENGLISH = 'en';
    const NORWEGIAN = 'no';

    /**
     * The config repository instance.
     *
     * @var Repository
     */
    protected $config;

    /**
     * The translator instance.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * @param Repository $config
     * @param Translator $translator
     */
    public function __construct(Repository $config, Translator $translator)
    {
        $this->config = $config;
        $this->translator = $translator;
    }

    /**
     * Returns all languages names supported indexed by their locales.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return collect($this->config->get('countries-partial'))
            ->pluck('locale', 'locale')
            ->transform(function ($locale) {
                return $this->translator->trans("i18n::language.$locale");
            });
    }

    /**
     * Filter out a specific language from all languages names supported indexed by their locales.
     *
     * @param string $localeToReject
     *
     * @return Collection
     */
    public function except(string $localeToReject): Collection
    {
        return $this
            ->all()
            ->reject(function ($language, $locale) use ($localeToReject) {
                return $locale === $localeToReject;
            });
    }
}
