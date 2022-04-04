<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Controller;

use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductSearchWidget\ProductSearchWidgetFactory getFactory()
 */
class ProductConcreteSearchController extends AbstractController
{
    /**
     * @uses ProductConcreteCatalogSearchResultFormatterPlugin::NAME
     *
     * @var string
     */
    protected const PRODUCT_CONCRETE_CATALOG_SEARCH_RESULT_FORMATTER_PLUGIN_NAME = 'ProductConcreteCatalogSearchResultFormatterPlugin';

    /**
     * @var string
     */
    protected const PARAM_SEARCH_STRING = 'searchString';

    /**
     * @var string
     */
    protected const PARAM_LIMIT = 'limit';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request): View
    {
        $productConcreteCriteriaFilterTransfer = $this->createProductConcreteCriteriaFilterTransfer($request);
        $products = $this->searchProducts($productConcreteCriteriaFilterTransfer);

        return $this->view(
            $products,
            [],
            '@ProductSearchWidget/views/product-search-results/product-search-results.twig',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return array
     */
    protected function searchProducts(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer): array
    {
        $formattedProducts = $this->getFactory()
            ->getCatalogClient()
            ->searchProductConcretesByFullText($productConcreteCriteriaFilterTransfer);

        return $formattedProducts[static::PRODUCT_CONCRETE_CATALOG_SEARCH_RESULT_FORMATTER_PLUGIN_NAME] ?? [];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer
     */
    protected function createProductConcreteCriteriaFilterTransfer(Request $request): ProductConcreteCriteriaFilterTransfer
    {
        $productConcreteCriteriaFilterTransfer = new ProductConcreteCriteriaFilterTransfer();

        $shopContextTransfer = $this->getFactory()
            ->createShopContextResolver()
            ->resolve();

        $requestParams = array_merge($shopContextTransfer->toArray(), $request->query->all());

        $productConcreteCriteriaFilterTransfer->setSearchString($request->get(static::PARAM_SEARCH_STRING));
        $productConcreteCriteriaFilterTransfer->setLimit($request->get(static::PARAM_LIMIT));
        $productConcreteCriteriaFilterTransfer->setRequestParams($requestParams);

        return $productConcreteCriteriaFilterTransfer;
    }
}
