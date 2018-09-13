<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Controller;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
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
    protected const PARAM_SEARCH_STRING = 'searchString';
    protected const PARAM_LIMIT = 'limit';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request): View
    {
        $products = $this->getFactory()->getProductPageSearchClient()->searchProductConcretesByFullText(
            $this->createProductConcreteCriteriaFilterTransfer($request)
        );

        return $this->view(
            $products[ProductConcretePageSearchResultFormatterPlugin::NAME] ?? [],
            [],
            '@ProductSearchWidget/views/product-search-results/product-search-results.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer
     */
    protected function createProductConcreteCriteriaFilterTransfer(Request $request): ProductConcreteCriteriaFilterTransfer
    {
        $productConcreteCriteriaFilterTransfer = new ProductConcreteCriteriaFilterTransfer();

        $filterTransfer = new FilterTransfer();
        $filterTransfer->setLimit($request->get(static::PARAM_LIMIT));

        $localeName = $this->getFactory()
            ->getLocaleClient()
            ->getCurrentLocale();

        $productConcreteCriteriaFilterTransfer->setLocale((new LocaleTransfer())->setLocaleName($localeName));
        $productConcreteCriteriaFilterTransfer->setSearchString($request->get(static::PARAM_SEARCH_STRING));
        $productConcreteCriteriaFilterTransfer->setFilter($filterTransfer);

        return $productConcreteCriteriaFilterTransfer;
    }
}
