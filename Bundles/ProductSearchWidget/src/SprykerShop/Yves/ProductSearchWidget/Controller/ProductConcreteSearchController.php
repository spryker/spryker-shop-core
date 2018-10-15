<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Controller;

use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Spryker\Client\ProductPageSearch\Plugin\Elasticsearch\ResultFormatter\ProductConcretePageSearchResultFormatterPlugin;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductSearchWidget\ProductSearchWidgetFactory getFactory()
 */
class ProductConcreteSearchController extends AbstractController
{
    /**
     * @uses ProductConcretePageSearchResultFormatterPlugin::NAME
     */
    protected const PRODUCT_CONCRETE_PAGE_SEARCH_RESULT_FORMATTER_PLUGIN_NAME = 'ProductConcretePageSearchResultFormatter';

    protected const PARAM_SEARCH_STRING = 'searchString';
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
            '@ProductSearchWidget/views/product-search-results/product-search-results.twig'
        );
    }

    /**
     * @param ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return array
     */
    protected function searchProducts(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer): array
    {
        $formattedProducts = $this->getFactory()
            ->getProductPageSearchClient()
            ->searchProductConcretesByFullText($productConcreteCriteriaFilterTransfer);

        return $formattedProducts[static::PRODUCT_CONCRETE_PAGE_SEARCH_RESULT_FORMATTER_PLUGIN_NAME] ?: [];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer
     */
    protected function createProductConcreteCriteriaFilterTransfer(Request $request): ProductConcreteCriteriaFilterTransfer
    {
        $productConcreteCriteriaFilterTransfer = new ProductConcreteCriteriaFilterTransfer();

        $productConcreteCriteriaFilterTransfer->setSearchString($request->get(static::PARAM_SEARCH_STRING));
        $productConcreteCriteriaFilterTransfer->setLimit($request->get(static::PARAM_LIMIT));

        return $productConcreteCriteriaFilterTransfer;
    }
}
