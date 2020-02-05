<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Controller;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Shared\Storage\StorageConstants;
use SprykerShop\Yves\ProductDetailPage\Exception\ProductAccessDeniedException;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Client\Product\ProductClientInterface getClient()
 * @method \SprykerShop\Yves\ProductDetailPage\ProductDetailPageFactory getFactory()
 */
class ProductController extends AbstractController
{
    public const ATTRIBUTE_PRODUCT_DATA = 'productData';

    public const PARAM_ATTRIBUTE = 'attribute';

    public const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_INCREMENTAL;

    protected const GLOSSARY_KEY_PRODUCT_ACCESS_DENIED = 'product.access.denied';

    /**
     * @param array $productData
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function detailAction(array $productData, Request $request)
    {
        $viewData = $this->executeDetailAction($productData, $request);

        return $this->view(
            $viewData,
            $this->getFactory()->getProductDetailPageWidgetPlugins(),
            '@ProductDetailPage/views/pdp/pdp.twig'
        );
    }

    /**
     * @param array $productData
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeDetailAction(array $productData, Request $request): array
    {
        $shopContextTransfer = $this->getFactory()->getShopContext();
        $productOfferStorageCriteriaTransfer = (new ProductOfferStorageCriteriaTransfer())
            ->fromArray($shopContextTransfer->toArray());
        $productViewTransfer = $this->getFactory()
            ->getProductStorageClient()
            ->mapProductStorageData($productData, $this->getLocale(), $this->getSelectedAttributes($request), $productOfferStorageCriteriaTransfer);

        $this->assertProductRestrictions($productViewTransfer);

        return [
            'product' => $productViewTransfer,
            'productUrl' => $this->getProductUrl($productViewTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function assertProductRestrictions(ProductViewTransfer $productViewTransfer): void
    {
        $this->assertProductAbstractRestrictions($productViewTransfer);
        $this->assertProductConcreteRestrictions($productViewTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @throws \SprykerShop\Yves\ProductDetailPage\Exception\ProductAccessDeniedException
     *
     * @return void
     */
    protected function assertProductAbstractRestrictions(ProductViewTransfer $productViewTransfer): void
    {
        if (empty($productViewTransfer->getIdProductAbstract())) {
            return;
        }

        $poductAbstractRestricted = $this->getFactory()
            ->getProductStorageClient()
            ->isProductAbstractRestricted($productViewTransfer->getIdProductAbstract());

        if ($poductAbstractRestricted) {
            throw new ProductAccessDeniedException(static::GLOSSARY_KEY_PRODUCT_ACCESS_DENIED);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @throws \SprykerShop\Yves\ProductDetailPage\Exception\ProductAccessDeniedException
     *
     * @return void
     */
    protected function assertProductConcreteRestrictions(ProductViewTransfer $productViewTransfer): void
    {
        if (empty($productViewTransfer->getIdProductConcrete())) {
            return;
        }

        $productConcreteRestricted = $this->getFactory()
            ->getProductStorageClient()
            ->isProductConcreteRestricted($productViewTransfer->getIdProductConcrete());

        if ($productConcreteRestricted) {
            throw new ProductAccessDeniedException(static::GLOSSARY_KEY_PRODUCT_ACCESS_DENIED);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return string
     */
    protected function getProductUrl(ProductViewTransfer $productViewTransfer)
    {
        if (!$productViewTransfer->getIdProductConcrete()) {
            return $productViewTransfer->getUrl();
        }

        $variantUriParams = [
            static::PARAM_ATTRIBUTE => $productViewTransfer->getSelectedAttributes(),
        ];

        return sprintf(
            '%s?%s',
            $productViewTransfer->getUrl(),
            http_build_query($variantUriParams)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getSelectedAttributes(Request $request)
    {
        return array_filter($request->query->get(self::PARAM_ATTRIBUTE, []));
    }
}
