<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\LanguageSwitcherWidget\Dependency\Client;

class LanguageSwitcherWidgetToLocaleClientBridge implements LanguageSwitcherWidgetToLocaleClientInterface
{
    /**
     * @var \Spryker\Client\Locale\LocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \Spryker\Client\Locale\LocaleClientInterface $localeClient
     */
    public function __construct($localeClient)
    {
        $this->localeClient = $localeClient;
    }

    /**
     * @return array<string>
     */
    public function getLocales(): array
    {
        return $this->localeClient->getLocales();
    }

    /**
     * @return string
     */
    public function getCurrentLanguage(): string
    {
        return $this->localeClient->getCurrentLanguage();
    }
}
