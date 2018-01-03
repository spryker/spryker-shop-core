<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CatalogPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CatalogPage\ActiveSearchFilter\UrlGenerator;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToCatalogClientInterface;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToCategoryStorageClientInterface;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToLocaleClientInterface;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToProductCategoryFilterClientInterface;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToSearchClientInterface;
use SprykerShop\Yves\CatalogPage\Twig\CatalogPageTwigExtension;

class CatalogPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CatalogPage\ActiveSearchFilter\UrlGeneratorInterface
     */
    public function createActiveSearchFilterUrlGenerator()
    {
        return new UrlGenerator($this->getSearchClient());
    }

    /**
     * @return \SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToCategoryStorageClientInterface
     */
    public function getCategoryStorageClient(): CatalogPageToCategoryStorageClientInterface
    {
        return $this->getProvidedDependency(CatalogPageDependencyProvider::CLIENT_CATEGORY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToLocaleClientInterface
     */
    public function getLocaleClient(): CatalogPageToLocaleClientInterface
    {
        return $this->getProvidedDependency(CatalogPageDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToSearchClientInterface
     */
    protected function getSearchClient(): CatalogPageToSearchClientInterface
    {
        return $this->getProvidedDependency(CatalogPageDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Shared\Twig\TwigExtension
     */
    public function createProductAbstractReviewTwigExtension()
    {
        return new CatalogPageTwigExtension($this->createActiveSearchFilterUrlGenerator());
    }

    /**
     * @return \SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToCatalogClientInterface
     */
    public function getCatalogClient(): CatalogPageToCatalogClientInterface
    {
        return $this->getProvidedDependency(CatalogPageDependencyProvider::CLIENT_CATALOG);
    }

    /**
     * @return string[]
     */
    public function getCatalogPageWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CatalogPageDependencyProvider::PLUGIN_CATALOG_PAGE_WIDGETS);
    }

    /**
     * @return CatalogPageToProductCategoryFilterClientInterface
     */
    public function getProductCategoryFilterClient(): CatalogPageToProductCategoryFilterClientInterface
    {
        return $this->getProvidedDependency(CatalogPageDependencyProvider::CLIENT_PRODUCT_CATEGORY_FILTER);
    }
}
