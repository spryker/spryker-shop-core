<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CmsPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CmsPage\Dependency\Client\CmsPageToCmsClientInterface;
use SprykerShop\Yves\CmsPage\Dependency\Client\CmsPageToCmsStorageClientInterface;
use SprykerShop\Yves\CmsPage\Dependency\Client\CmsPageToCustomerClientInterface;

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
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(CmsPageDependencyProvider::STORE);
    }

    /**
     * @return \SprykerShop\Yves\CmsPage\Dependency\Client\CmsPageToCmsStorageClientInterface
     */
    public function getCmsStorageClient(): CmsPageToCmsStorageClientInterface
    {
        return $this->getProvidedDependency(CmsPageDependencyProvider::CLIENT_CMS_STORAGE);
    }
}
