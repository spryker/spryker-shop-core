<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToStoreClientInterface;
use SprykerShop\Yves\CmsBlockWidget\Twig\CmsBlockTwigFunction;

class CmsBlockWidgetFactory extends AbstractFactory
{
    /**
     * @param string $localeName
     *
     * @return \SprykerShop\Yves\CmsBlockWidget\Twig\CmsBlockTwigFunction
     */
    public function createCmsBlockTwigFunction(string $localeName): CmsBlockTwigFunction
    {
        return new CmsBlockTwigFunction(
            $this->getCmsBlockStorageClient(),
            $this->getStoreClient(),
            $localeName
        );
    }

    /**
     * @return \SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToStoreClientInterface
     */
    public function getStoreClient(): CmsBlockWidgetToStoreClientInterface
    {
        return $this->getProvidedDependency(CmsBlockWidgetDependencyProvider::CLIENT_STORE);
    }

    /**
     * @deprecated Use `getTwigExtensionPlugins` instead.
     *
     * @return \Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface[]
     */
    public function getTwigFunctionPlugins()
    {
        return $this->getProvidedDependency(CmsBlockWidgetDependencyProvider::TWIG_FUNCTION_PLUGINS);
    }

    /**
     * @return \Spryker\Shared\Twig\TwigExtensionInterface[]
     */
    public function getTwigExtensionPlugins()
    {
        return $this->getProvidedDependency(CmsBlockWidgetDependencyProvider::TWIG_EXTENSION_PLUGINS);
    }

    /**
     * @return \SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientInterface
     */
    public function getCmsBlockStorageClient()
    {
        return $this->getProvidedDependency(CmsBlockWidgetDependencyProvider::CLIENT_CMS_BLOCK_STORAGE);
    }
}
