<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Controller;

use Generated\Shared\Transfer\ProductStorageCriteriaTransfer;
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
    /**
     * @var string
     */
    public const ATTRIBUTE_PRODUCT_DATA = 'productData';

    /**
     * @var string
     */
    public const PARAM_ATTRIBUTE = 'attribute';

    /**
     * @var string
     */
    public const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_INCREMENTAL;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_ACCESS_DENIED = 'product.access.denied';

    /**
     * @uses \Generated\Shared\Transfer\AttributeMapStorageTransfer::SUPER_ATTRIBUTES
     *
     * @var string
     */
    protected const PRODUCT_DATA_KEY_SUPER_ATTRIBUTES = 'super_attributes';

    /**
     * @uses \Generated\Shared\Transfer\ProductAbstractStorageTransfer::ATTRIBUTE_MAP
     *
     * @var string
     */
    protected const PRODUCT_DATA_KEY_ATTRIBUTE_MAP = 'attribute_map';

    /**
     * @param array<mixed> $productData
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
            '@ProductDetailPage/views/pdp/pdp.twig',
        );
    }

    /**
     * @param array<string, mixed> $productData
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    protected function executeDetailAction(array $productData, Request $request): array
    {
        $shopContextTransfer = $this->getFactory()
            ->createShopContextResolver()
            ->resolve();

        $productStorageCriteriaTransfer = (new ProductStorageCriteriaTransfer())
            ->fromArray($shopContextTransfer->toArray());

        $selectedAttributes = $this->getSelectedAttributesWithoutPostfix($productData, $request);

        $productViewTransfer = $this->getFactory()
            ->getProductStorageClient()
            ->mapProductStorageData(
                $productData,
                $this->getLocale(),
                $selectedAttributes,
                $productStorageCriteriaTransfer,
            );

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
        if (!$productViewTransfer->getIdProductAbstract()) {
            return;
        }

        $productAbstractRestricted = $this->getFactory()
            ->getProductStorageClient()
            ->isProductAbstractRestricted($productViewTransfer->getIdProductAbstract());

        if ($productAbstractRestricted) {
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
        if (!$productViewTransfer->getIdProductConcrete()) {
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
            http_build_query($variantUriParams),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<mixed>
     */
    protected function getSelectedAttributes(Request $request): array
    {
        /** @var array<mixed> $selectedAttributes */
        $selectedAttributes = $request->query->all()[static::PARAM_ATTRIBUTE] ?? [];

        return array_filter($selectedAttributes, function ($selectedAttributeValue) {
            return (bool)mb_strlen($selectedAttributeValue);
        });
    }

    /**
     * @param array<string, mixed> $productData
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, string>
     */
    protected function getSelectedAttributesWithoutPostfix(array $productData, Request $request): array
    {
        $selectedAttributes = $this->getSelectedAttributes($request);
        if (!isset($productData[static::PRODUCT_DATA_KEY_ATTRIBUTE_MAP][static::PRODUCT_DATA_KEY_SUPER_ATTRIBUTES])) {
            return $selectedAttributes;
        }
        $productSuperAttributes = $productData[static::PRODUCT_DATA_KEY_ATTRIBUTE_MAP][static::PRODUCT_DATA_KEY_SUPER_ATTRIBUTES];

        foreach ($selectedAttributes as $selectedAttributeKey => $selectedAttributeValue) {
            if (isset($productSuperAttributes[$selectedAttributeKey])) {
                $selectedAttributes[$selectedAttributeKey] = $this->getSelectedAttributeValueWithoutPostfix(
                    $productSuperAttributes[$selectedAttributeKey],
                    $selectedAttributeValue,
                );
            }
        }

        return $selectedAttributes;
    }

    /**
     * @param list<string> $superAttributeValues
     * @param string $selectedAttributeValue
     *
     * @return string
     */
    protected function getSelectedAttributeValueWithoutPostfix(
        array $superAttributeValues,
        string $selectedAttributeValue
    ): string {
        $maxContainedSuperAttributeValue = '';
        foreach ($superAttributeValues as $superAttributeValue) {
            if (
                strrpos($selectedAttributeValue, $superAttributeValue) !== false &&
                strlen($superAttributeValue) > strlen($maxContainedSuperAttributeValue)
            ) {
                $maxContainedSuperAttributeValue = $superAttributeValue;
            }
        }

        return $maxContainedSuperAttributeValue;
    }
}
