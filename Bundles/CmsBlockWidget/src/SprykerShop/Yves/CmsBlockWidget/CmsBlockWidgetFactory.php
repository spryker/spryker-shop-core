<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\CmsBlockWidget\src\SprykerShop\Yves\CmsBlockWidget\Validator\CmsBlockValidator;
use SprykerShop\CmsBlockWidget\src\SprykerShop\Yves\CmsBlockWidget\Validator\CmsBlockValidatorInterface;
use SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientInterface;
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
            $this->createCmsBlockValidator(),
            $localeName
        );
    }

    /**
     * @return \SprykerShop\CmsBlockWidget\src\SprykerShop\Yves\CmsBlockWidget\Validator\CmsBlockValidatorInterface
     */
    public function createCmsBlockValidator(): CmsBlockValidatorInterface
    {
        return new CmsBlockValidator();
    }

    /**
     * @return \SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToStoreClientInterface
     */
    public function getStoreClient(): CmsBlockWidgetToStoreClientInterface
    {
        return $this->getProvidedDependency(CmsBlockWidgetDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Shared\Twig\TwigExtensionInterface[]
     */
    public function getTwigExtensionPlugins(): array
    {
        return $this->getProvidedDependency(CmsBlockWidgetDependencyProvider::TWIG_EXTENSION_PLUGINS);
    }

    /**
     * @return \SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientInterface
     */
    public function getCmsBlockStorageClient(): CmsBlockWidgetToCmsBlockStorageClientInterface
    {
        return $this->getProvidedDependency(CmsBlockWidgetDependencyProvider::CLIENT_CMS_BLOCK_STORAGE);
    }
}
