<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FeaturedProductWidget\Plugin\HomePage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\HomePage\Dependency\Plugin\FeaturedProductWidget\FeaturedProductListWidgetInterface;

/**
 * @method \SprykerShop\Yves\FeaturedProductWidget\FeaturedProductWidgetFactory getFactory()
 */
class FeaturedProductListWidgetPlugin extends AbstractWidgetPlugin implements FeaturedProductListWidgetInterface
{

    /**
     * @param int $limit
     *
     * @return void
     */
    public function initialize(int $limit): void
    {
        $this
            ->addParameter('searchResult', $this->getSearchResults($limit))
            ->addWidgets($this->getFactory()->getFeaturedProductSubWidgetPlugins());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@FeaturedProductWidget/_home-page/products.twig';
    }

    /**
     * @param int $limit
     *
     * @return array
     */
    protected function getSearchResults(int $limit): array
    {
        return $this->getFactory()
            ->getCatalogClient()
            ->getFeaturedProducts($limit);
    }

}
