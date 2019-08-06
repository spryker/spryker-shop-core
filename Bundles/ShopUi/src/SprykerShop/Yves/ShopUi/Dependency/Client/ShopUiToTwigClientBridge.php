<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Dependency\Client;

class ShopUiToTwigClientBridge implements ShopUiToTwigClientInterface
{
    /**
     * @var \Spryker\Client\Twig\TwigClientInterface
     */
    protected $twigClient;

    /**
     * @param \Spryker\Client\Twig\TwigClientInterface $twigClient
     */
    public function __construct($twigClient)
    {
        $this->twigClient = $twigClient;
    }

    /**
     * @return string
     */
    public function getYvesThemeName(): string
    {
        return $this->twigClient->getYvesThemeName();
    }

    /**
     * @return string
     */
    public function getYvesThemeNameDefault(): string
    {
        return $this->twigClient->getYvesThemeNameDefault();
    }
}
