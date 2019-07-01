<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ShopUi\Dependency\Client\ShopUiToTwigClientInterface;
use SprykerShop\Yves\ShopUi\Twig\Assets\AssetsUrlProvider;
use SprykerShop\Yves\ShopUi\Twig\Assets\AssetsUrlProviderInterface;
use SprykerShop\Yves\ShopUi\Twig\ShopUiTwigExtension;

/**
 * @method \SprykerShop\Yves\ShopUi\ShopUiConfig getConfig()
 */
class ShopUiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Twig\TwigExtension
     */
    public function createShopUiTwigExtension()
    {
        return new ShopUiTwigExtension(
            $this->createAssetsUrlProvider()
        );
    }

    /**
     * @return \SprykerShop\Yves\ShopUi\Twig\Assets\AssetsUrlProviderInterface
     */
    public function createAssetsUrlProvider(): AssetsUrlProviderInterface
    {
        return new AssetsUrlProvider(
            $this->getConfig(),
            $this->getTwigClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\ShopUi\Dependency\Client\ShopUiToTwigClientInterface
     */
    public function getTwigClient(): ShopUiToTwigClientInterface
    {
        return $this->getProvidedDependency(ShopUiDependencyProvider::CLIENT_TWIG);
    }
}
