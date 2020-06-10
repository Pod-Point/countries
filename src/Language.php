<?php

namespace PodPoint\I18n;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Translation\Translator;

class Language
{
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
     * @return array
     */
    public function all(): array
    {
        return collect($this->config->get('countries-partial'))
            ->pluck('locale', 'locale')
            ->transform(function ($locale) {
                return $this->translator->trans("i18n::language.$locale");
            })
            ->toArray();
    }
}
