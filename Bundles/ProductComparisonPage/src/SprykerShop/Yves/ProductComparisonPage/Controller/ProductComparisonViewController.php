<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductComparisonPage\Controller;

use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductComparisonPage\ProductComparisonPageConfig getConfig()
 * @method \SprykerShop\Yves\ProductComparisonPage\ProductComparisonPageFactory getFactory()
 */
class ProductComparisonViewController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_KEY_SKUS = 'skus';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_PRODUCTS = 'products';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_ATTRIBUTES = 'attributes';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request): View
    {
        return $this->view(
            $this->executeIndexAction($request->get(static::REQUEST_KEY_SKUS, '')),
            [],
            '@ProductComparisonPage/views/product-comparison/product-comparison.twig',
        );
    }

    /**
     * @param string $skus
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductViewTransfer>|array<mixed>>
     */
    protected function executeIndexAction(string $skus): array
    {
        $skuList = array_filter(explode(',', $skus));
        if ($skuList === []) {
            return [
                static::RESPONSE_KEY_PRODUCTS => [],
                static::RESPONSE_KEY_ATTRIBUTES => [],
            ];
        }

        $productViewTransfers = $this->getFactory()
            ->createProductComparisonListReader()
            ->getProductsCompareList($skuList, $this->getLocale());

        $productAttributes = $this->getFactory()
            ->createProductAttributeCollector()
            ->collectUniqueProductAttributes($productViewTransfers);

        return [
            static::RESPONSE_KEY_PRODUCTS => $productViewTransfers,
            static::RESPONSE_KEY_ATTRIBUTES => $productAttributes,
        ];
    }
}
