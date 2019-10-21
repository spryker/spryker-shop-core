<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToConfigurableBundleCartClientInterface;
use SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToQuoteClientInterface;
use SprykerShop\Yves\ConfigurableBundleWidget\Grouper\ConfiguredBundleGrouper;
use SprykerShop\Yves\ConfigurableBundleWidget\Grouper\ConfiguredBundleGrouperInterface;
use SprykerShop\Yves\ConfigurableBundleWidget\Mapper\ConfiguredBundleMapper;
use SprykerShop\Yves\ConfigurableBundleWidget\Mapper\ConfiguredBundleMapperInterface;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleWidget\ConfigurableBundleWidgetConfig getConfig()
 */
class ConfigurableBundleWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ConfigurableBundleWidget\Grouper\ConfiguredBundleGrouperInterface
     */
    public function createConfiguredBundleGrouper(): ConfiguredBundleGrouperInterface
    {
        return new ConfiguredBundleGrouper(
            $this->createConfiguredBundleMapper()
        );
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundleWidget\Mapper\ConfiguredBundleMapperInterface
     */
    public function createConfiguredBundleMapper(): ConfiguredBundleMapperInterface
    {
        return new ConfiguredBundleMapper();
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundleWidget\ConfigurableBundleWidgetConfig
     */
    public function getModuleConfig(): ConfigurableBundleWidgetConfig
    {
        return $this->getConfig();
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToConfigurableBundleCartClientInterface
     */
    public function getConfigurableBundleClient(): ConfigurableBundleWidgetToConfigurableBundleCartClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleWidgetDependencyProvider::CLIENT_CONFIGURABLE_BUNDLE_CART);
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToQuoteClientInterface
     */
    public function getQuoteClient(): ConfigurableBundleWidgetToQuoteClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleWidgetDependencyProvider::CLIENT_QUOTE);
    }
}
