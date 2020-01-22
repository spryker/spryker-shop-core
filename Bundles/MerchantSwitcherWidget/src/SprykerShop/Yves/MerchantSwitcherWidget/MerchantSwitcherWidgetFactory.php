<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantSwitcherWidget\ActiveMerchantReader\ActiveMerchantReader;
use SprykerShop\Yves\MerchantSwitcherWidget\ActiveMerchantReader\ActiveMerchantReaderInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface;

/**
 * @method \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig getConfig()
 */
class MerchantSwitcherWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\MerchantSwitcherWidget\ActiveMerchantReader\ActiveMerchantReaderInterface
     */
    public function createActiveMerchantReader(): ActiveMerchantReaderInterface
    {
        return new ActiveMerchantReader(
            $this->getMerchantSearchClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface
     */
    public function getMerchantSearchClient(): MerchantSwitcherWidgetToMerchantSearchClientInterface
    {
        return $this->getProvidedDependency(MerchantSwitcherWidgetDependencyProvider::CLIENT_MERCHANT_SEARCH);
    }
}
