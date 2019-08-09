<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ConfigurableBundleWidget\Calculator\ConfiguredBundlePriceCalculator;
use SprykerShop\Yves\ConfigurableBundleWidget\Calculator\ConfiguredBundlePriceCalculatorInterface;
use SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToCartClientInterface;
use SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToQuoteClientInterface;
use SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToZedRequestClientInterface;
use SprykerShop\Yves\ConfigurableBundleWidget\Mapper\ConfiguredBundleMapper;
use SprykerShop\Yves\ConfigurableBundleWidget\Mapper\ConfiguredBundleMapperInterface;
use SprykerShop\Yves\ConfigurableBundleWidget\Reader\QuoteReader;
use SprykerShop\Yves\ConfigurableBundleWidget\Reader\QuoteReaderInterface;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleWidget\ConfigurableBundleWidgetConfig getConfig()
 */
class ConfigurableBundleWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ConfigurableBundleWidget\Reader\QuoteReaderInterface
     */
    public function createQuoteReader(): QuoteReaderInterface
    {
        return new QuoteReader(
            $this->getCartClient()
        );
    }

    public function createConfiguredBundleMapper(): ConfiguredBundleMapperInterface
    {
        return new ConfiguredBundleMapper(
            $this->createConfiguredBundlePriceCalculator()
        );
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundleWidget\Calculator\ConfiguredBundlePriceCalculatorInterface
     */
    public function createConfiguredBundlePriceCalculator(): ConfiguredBundlePriceCalculatorInterface
    {
        return new ConfiguredBundlePriceCalculator();
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToZedRequestClientInterface
     */
    public function getZedRequestClient(): ConfigurableBundleWidgetToZedRequestClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleWidgetDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToCartClientInterface
     */
    public function getCartClient(): ConfigurableBundleWidgetToCartClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleWidgetDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToQuoteClientInterface
     */
    public function getQuoteClient(): ConfigurableBundleWidgetToQuoteClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleWidgetDependencyProvider::CLIENT_QUOTE);
    }
}
