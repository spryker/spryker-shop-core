<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSlotBlockWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CmsSlotBlockWidget\Business\CmsSlotBlockWidgetDataProvider;
use SprykerShop\Yves\CmsSlotBlockWidget\Business\CmsSlotBlockWidgetDataProviderInterface;
use SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockStorageClientInterface;
use Twig\Environment;

/**
 * @method \SprykerShop\Yves\CmsSlotBlockWidget\CmsSlotBlockWidgetConfig getConfig()
 */
class CmsSlotBlockWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CmsSlotBlockWidget\Business\CmsSlotBlockWidgetDataProviderInterface
     */
    public function createCmsSlotBlockWidgetDataProvider(): CmsSlotBlockWidgetDataProviderInterface
    {
        return new CmsSlotBlockWidgetDataProvider(
            $this->getTwigEnvironment(),
            $this->getCmsSlotBlockStorageClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockStorageClientInterface
     */
    public function getCmsSlotBlockStorageClient(): CmsSlotBlockWidgetToCmsSlotBlockStorageClientInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockWidgetDependencyProvider::CLIENT_CMS_SLOT_BLOCK_STORAGE);
    }

    /**
     * @return \Twig\Environment
     */
    public function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(CmsSlotBlockWidgetDependencyProvider::TWIG_ENVIRONMENT);
    }
}
