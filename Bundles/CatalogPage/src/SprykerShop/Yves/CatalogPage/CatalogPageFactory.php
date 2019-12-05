<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CatalogPage\ActiveSearchFilter\UrlGenerator;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToCatalogClientInterface;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToCategoryStorageClientInterface;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToLocaleClientInterface;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToProductCategoryFilterClientInterface;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToProductCategoryFilterStorageClientInterface;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToSearchClientInterface;
use SprykerShop\Yves\CatalogPage\FacetFilter\FacetFilter;
use SprykerShop\Yves\CatalogPage\FacetFilter\FacetFilterInterface;
use SprykerShop\Yves\CatalogPage\Purifier\RequestAttributesPurifier;
use SprykerShop\Yves\CatalogPage\Purifier\RequestAttributesPurifierInterface;
use SprykerShop\Yves\CatalogPage\Twig\CatalogPageTwigExtension;
use SprykerShop\Yves\CatalogPage\Validator\PageParametersValidator;
use SprykerShop\Yves\CatalogPage\Validator\PageParametersValidatorInterface;

/**
 * @method \SprykerShop\Yves\CatalogPage\CatalogPageConfig getConfig()
 */
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
     * @return \SprykerShop\Yves\CatalogPage\FacetFilter\FacetFilterInterface
     */
    public function createFacetFilter(): FacetFilterInterface
    {
        return new FacetFilter();
    }

    /**
     * @return \SprykerShop\Yves\CatalogPage\Validator\PageParametersValidatorInterface
     */
    public function createPageParametersValidator(): PageParametersValidatorInterface
    {
        return new PageParametersValidator($this->getConfig());
    }

    /**
     * @return \SprykerShop\Yves\CatalogPage\Purifier\RequestAttributesPurifierInterface
     */
    public function createRequestAttributesPurifier(): RequestAttributesPurifierInterface
    {
        return new RequestAttributesPurifier();
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
    public function getSearchClient(): CatalogPageToSearchClientInterface
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
     * @return \SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToProductCategoryFilterClientInterface
     */
    public function getProductCategoryFilterClient(): CatalogPageToProductCategoryFilterClientInterface
    {
        return $this->getProvidedDependency(CatalogPageDependencyProvider::CLIENT_PRODUCT_CATEGORY_FILTER);
    }

    /**
     * @return \SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToProductCategoryFilterStorageClientInterface
     */
    public function getProductCategoryFilterStorageClient(): CatalogPageToProductCategoryFilterStorageClientInterface
    {
        return $this->getProvidedDependency(CatalogPageDependencyProvider::CLIENT_PRODUCT_CATEGORY_FILTER_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\CatalogPage\CatalogPageConfig
     */
    public function getModuleConfig(): CatalogPageConfig
    {
        return $this->getConfig();
    }
}
