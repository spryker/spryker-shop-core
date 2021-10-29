<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CmsPage\Dependency\Client\CmsPageToCmsClientInterface;
use SprykerShop\Yves\CmsPage\Dependency\Client\CmsPageToCmsStorageClientInterface;
use SprykerShop\Yves\CmsPage\Dependency\Client\CmsPageToCustomerClientInterface;
use SprykerShop\Yves\CmsPage\Dependency\Client\CmsPageToLocaleClientInterface;

class CmsPageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\CmsContentWidget\Plugin\CmsTwigContentRendererPluginInterface
     */
    public function getCmsTwigRendererPlugin()
    {
        return $this->getProvidedDependency(CmsPageDependencyProvider::CMS_TWIG_CONTENT_RENDERER_PLUGIN);
    }

    /**
     * @return \SprykerShop\Yves\CmsPage\Dependency\Client\CmsPageToCmsClientInterface
     */
    public function getCmsClient(): CmsPageToCmsClientInterface
    {
        return $this->getProvidedDependency(CmsPageDependencyProvider::CLIENT_CMS);
    }

    /**
     * @return \SprykerShop\Yves\CmsPage\Dependency\Client\CmsPageToCustomerClientInterface
     */
    public function getCustomerClient(): CmsPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(CmsPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\CmsPage\Dependency\Client\CmsPageToCmsStorageClientInterface
     */
    public function getCmsStorageClient(): CmsPageToCmsStorageClientInterface
    {
        return $this->getProvidedDependency(CmsPageDependencyProvider::CLIENT_CMS_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\CmsPage\Dependency\Client\CmsPageToLocaleClientInterface
     */
    public function getLocaleClient(): CmsPageToLocaleClientInterface
    {
        return $this->getProvidedDependency(CmsPageDependencyProvider::CLIENT_LOCALE);
    }
}
