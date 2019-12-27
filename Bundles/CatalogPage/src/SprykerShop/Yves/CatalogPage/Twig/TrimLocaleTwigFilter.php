<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Twig;

use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Twig\TwigFilter;

class TrimLocaleTwigFilter extends TwigFilter
{
    protected const FILTER_TRIM_LOCALE = 'trimLocale';

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var string
     */
    protected $localesFilterPattern;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
        parent::__construct($this->getFilterName(), $this->getFilter());
    }

    /**
     * @return string
     */
    public function getFilterName(): string
    {
        return static::FILTER_TRIM_LOCALE;
    }

    /**
     * @return callable
     */
    public function getFilter(): callable
    {
        return function (string $filterValue): string {
            return $this->trimLocale($filterValue);
        };
    }

    /**
     * @param string $filterValue
     *
     * @return string
     */
    protected function trimLocale(string $filterValue): string
    {
        return preg_replace(
            $this->getLocalesPattern(),
            '/',
            $filterValue
        );
    }

    /**
     * @return string
     */
    protected function getLocalesPattern(): string
    {
        if ($this->localesFilterPattern) {
            return $this->localesFilterPattern;
        }

        $locales = $this->store->getLocales();
        $implodedLocales = implode('|', array_keys($locales));
        $this->localesFilterPattern = '#^\/(' . $implodedLocales . ')\/#';

        return $this->localesFilterPattern;
    }
}
