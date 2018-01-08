<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class CmsBlockWidgetFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface[]
     */
    public function getTwigFunctionPlugins()
    {
        return $this->getProvidedDependency(CmsBlockWidgetDependencyProvider::TWIG_FUNCTION_PLUGINS);
    }

    /**
     * @return \SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientInterface
     */
    public function getCmsBlockStorageClient()
    {
        return $this->getProvidedDependency(CmsBlockWidgetDependencyProvider::CLIENT_CMS_BLOCK_STORAGE);
    }
}
