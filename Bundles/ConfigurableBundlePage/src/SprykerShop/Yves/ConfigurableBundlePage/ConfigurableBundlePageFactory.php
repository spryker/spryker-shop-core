<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundlePageSearchClientInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleStorageClientInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Validator\ConfigurableBundleTemplateSlotCombinationValidator;
use SprykerShop\Yves\ConfigurableBundlePage\Validator\ConfigurableBundleTemplateSlotCombinationValidatorInterface;

class ConfigurableBundlePageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Validator\ConfigurableBundleTemplateSlotCombinationValidatorInterface
     */
    public function createConfigurableBundleTemplateSlotCombinationValidator(): ConfigurableBundleTemplateSlotCombinationValidatorInterface
    {
        return new ConfigurableBundleTemplateSlotCombinationValidator();
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundlePageSearchClientInterface
     */
    public function getConfigurableBundlePageSearchClient(): ConfigurableBundlePageToConfigurableBundlePageSearchClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlePageDependencyProvider::CLIENT_CONFIGURABLE_BUNDLE_PAGE_SEARCH);
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleStorageClientInterface
     */
    public function getConfigurableBundleStorageClient(): ConfigurableBundlePageToConfigurableBundleStorageClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlePageDependencyProvider::CLIENT_CONFIGURABLE_BUNDLE_STORAGE);
    }
}
