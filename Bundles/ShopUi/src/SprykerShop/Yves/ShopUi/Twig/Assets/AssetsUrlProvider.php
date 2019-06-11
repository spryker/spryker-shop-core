<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Twig\Assets;

use SprykerShop\Yves\ShopUi\Dependency\Client\ShopUiToTwigClientInterface;
use SprykerShop\Yves\ShopUi\ShopUiConfig;

class AssetsUrlProvider implements AssetsUrlProviderInterface
{
    protected const STORE_KEY = '%store%';
    protected const THEME_KEY = '%theme%';

    /**
     * @var \SprykerShop\Yves\ShopUi\ShopUiConfig
     */
    protected $shopUiConfig;

    /**
     * @var string
     */
    protected $storeName;

    /**
     * @var \SprykerShop\Yves\ShopUi\Dependency\Client\ShopUiToTwigClientInterface
     */
    protected $twigClient;

    /**
     * @param \SprykerShop\Yves\ShopUi\ShopUiConfig $shopUiConfig
     * @param \SprykerShop\Yves\ShopUi\Dependency\Client\ShopUiToTwigClientInterface $twigClient
     * @param string $storeName
     */
    public function __construct(
        ShopUiConfig $shopUiConfig,
        ShopUiToTwigClientInterface $twigClient,
        string $storeName
    ) {
        $this->shopUiConfig = $shopUiConfig;
        $this->storeName = $storeName;
        $this->twigClient = $twigClient;
    }

    /**
     * @return string
     */
    public function getAssetsUrl(): string
    {
        $yvesAssetsUrl = strtr($this->shopUiConfig->getYvesAssetsUrlPattern(), [
            static::STORE_KEY => $this->getStoreKey(),
            static::THEME_KEY => $this->getThemeKey(),
        ]);

        $yvesAssetsUrl = rtrim($yvesAssetsUrl, '/');

        return $yvesAssetsUrl . '/';
    }

    /**
     * @return string
     */
    protected function getThemeKey(): string
    {
        $themeName = $this->twigClient->getYvesThemeName();

        if (!$themeName) {
            $themeName = $this->twigClient->getYvesThemeNameDefault();
        }

        return strtolower($themeName);
    }

    /**
     * @return string
     */
    protected function getStoreKey(): string
    {
        return strtolower($this->storeName);
    }
}
