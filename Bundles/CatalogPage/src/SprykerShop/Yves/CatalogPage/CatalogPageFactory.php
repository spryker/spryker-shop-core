<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CatalogPage;

use Spryker\Client\Catalog\CatalogClient;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CatalogPage\ActiveSearchFilter\UrlGenerator;
use SprykerShop\Yves\CatalogPage\ResourceCreator\CategoryResourceCreator;
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
     * @return \Spryker\Client\Category\CategoryClientInterface
     */
    public function getCategoryClient()
    {
        return $this->getProvidedDependency(CatalogPageDependencyProvider::CLIENT_CATEGORY);
    }

    /**
     * @return \Spryker\Client\Locale\LocaleClientInterface
     */
    public function getLocaleClient()
    {
        return $this->getProvidedDependency(CatalogPageDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    protected function getSearchClient()
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
     * @return \Pyz\Yves\Collector\Creator\ResourceCreatorInterface
     */
    public function createCategoryResourceCreator()
    {
        return new CategoryResourceCreator();
    }

    /**
     * @return \Spryker\Client\Catalog\CatalogClientInterface
     */
    public function getCatalogClient()
    {
        return new CatalogClient(); // TODO: move to dependency provider
    }

    /**
     * @return \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface[]
     */
    public function getCatalogWidgetBuilderPlugins()
    {
        return $this->getProvidedDependency(CatalogPageDependencyProvider::PLUGIN_CATALOG_WIDGET_BUILDERS);
    }

}
