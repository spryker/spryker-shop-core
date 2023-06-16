<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ShopUi\Dependency\Client\ShopUiToLocaleClientInterface;
use SprykerShop\Yves\ShopUi\Dependency\Client\ShopUiToTwigClientInterface;
use SprykerShop\Yves\ShopUi\Dependency\Service\ShopUiToUtilNumberServiceInterface;
use SprykerShop\Yves\ShopUi\Dependency\Service\ShopUiToUtilSanitizeXssServiceInterface;
use SprykerShop\Yves\ShopUi\Extender\NumberFormatterTwigExtender;
use SprykerShop\Yves\ShopUi\Extender\NumberFormatterTwigExtenderInterface;
use SprykerShop\Yves\ShopUi\Filter\NumberFormatterTwigFilterFactory;
use SprykerShop\Yves\ShopUi\Filter\NumberFormatterTwigFilterFactoryInterface;
use SprykerShop\Yves\ShopUi\Form\Type\Extension\SanitizeXssTypeExtension;
use SprykerShop\Yves\ShopUi\Twig\Assets\AssetsUrlProvider;
use SprykerShop\Yves\ShopUi\Twig\Assets\AssetsUrlProviderInterface;
use SprykerShop\Yves\ShopUi\Twig\ShopUiTwigExtension;
use SprykerShop\Yves\ShopUi\TwigFunction\NumberFormatterTwigFunctionFactory;
use SprykerShop\Yves\ShopUi\TwigFunction\NumberFormatterTwigFunctionFactoryInterface;
use Symfony\Component\Form\FormTypeExtensionInterface;

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
            $this->getLocaleClient(),
            $this->getConfig(),
            $this->createAssetsUrlProvider(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ShopUi\Twig\Assets\AssetsUrlProviderInterface
     */
    public function createAssetsUrlProvider(): AssetsUrlProviderInterface
    {
        return new AssetsUrlProvider(
            $this->getConfig(),
            $this->getTwigClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ShopUi\Extender\NumberFormatterTwigExtenderInterface
     */
    public function createNumberFormatterTwigExtender(): NumberFormatterTwigExtenderInterface
    {
        return new NumberFormatterTwigExtender(
            $this->createNumberFormatterTwigFilterFactory(),
            $this->createNumberFormatterTwigFunctionFactory(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ShopUi\Filter\NumberFormatterTwigFilterFactoryInterface
     */
    public function createNumberFormatterTwigFilterFactory(): NumberFormatterTwigFilterFactoryInterface
    {
        return new NumberFormatterTwigFilterFactory(
            $this->getUtilNumberService(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ShopUi\TwigFunction\NumberFormatterTwigFunctionFactoryInterface
     */
    public function createNumberFormatterTwigFunctionFactory(): NumberFormatterTwigFunctionFactoryInterface
    {
        return new NumberFormatterTwigFunctionFactory(
            $this->getUtilNumberService(),
        );
    }

    /**
     * @return \Symfony\Component\Form\FormTypeExtensionInterface
     */
    public function createSanitizeXssTypeExtension(): FormTypeExtensionInterface
    {
        return new SanitizeXssTypeExtension($this->getUtilSanitizeXssService());
    }

    /**
     * @return \SprykerShop\Yves\ShopUi\Dependency\Client\ShopUiToTwigClientInterface
     */
    public function getTwigClient(): ShopUiToTwigClientInterface
    {
        return $this->getProvidedDependency(ShopUiDependencyProvider::CLIENT_TWIG);
    }

    /**
     * @return \SprykerShop\Yves\ShopUi\Dependency\Client\ShopUiToLocaleClientInterface
     */
    public function getLocaleClient(): ShopUiToLocaleClientInterface
    {
        return $this->getProvidedDependency(ShopUiDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \SprykerShop\Yves\ShopUi\Dependency\Service\ShopUiToUtilNumberServiceInterface
     */
    public function getUtilNumberService(): ShopUiToUtilNumberServiceInterface
    {
        return $this->getProvidedDependency(ShopUiDependencyProvider::SERVICE_UTIL_NUMBER);
    }

    /**
     * @return \SprykerShop\Yves\ShopUi\Dependency\Service\ShopUiToUtilSanitizeXssServiceInterface
     */
    public function getUtilSanitizeXssService(): ShopUiToUtilSanitizeXssServiceInterface
    {
        return $this->getProvidedDependency(ShopUiDependencyProvider::SERVICE_UTIL_SANITIZE_XSS);
    }
}
