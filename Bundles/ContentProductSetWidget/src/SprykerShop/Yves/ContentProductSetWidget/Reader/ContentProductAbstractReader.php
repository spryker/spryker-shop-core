<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductSetWidget\Reader;

use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToProductStorageClientInterface;
use Symfony\Component\HttpFoundation\Request;

class ContentProductAbstractReader implements ContentProductAbstractReaderInterface
{
    /**
     * @uses \SprykerShop\Yves\ProductSetDetailPage\Controller\DetailController::PARAM_ATTRIBUTE
     */
    protected const PARAM_ATTRIBUTE = 'attributes';

    /**
     * @var \SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToProductStorageClientInterface $productStorageClient
     */
    public function __construct(ContentProductSetWidgetToProductStorageClientInterface $productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]|null
     */
    public function findProductAbstractCollection(
        ProductSetDataStorageTransfer $productSetDataStorageTransfer,
        Request $request,
        string $localeName
    ): ?array {
        $productAbstractViewCollection = [];
        $requestAttributes = $request->query->get(static::PARAM_ATTRIBUTE, []);

        foreach ($productSetDataStorageTransfer->getProductAbstractIds() as $idProductAbstract) {
            $productAbstract = $this->productStorageClient->findProductAbstractStorageData($idProductAbstract, $localeName);

            if (!$productAbstract) {
                continue;
            }

            $selectedAttributes = $this->getSelectedAttributesByIdProductAbstract($idProductAbstract, $requestAttributes);
            $productAbstractViewCollection[] = $this->productStorageClient->mapProductAbstractStorageData($productAbstract, $localeName, $selectedAttributes);
        }

        return $productAbstractViewCollection;
    }

    /**
     * @param int $idProductAbstract
     * @param array $attributes
     *
     * @return array
     */
    protected function getSelectedAttributesByIdProductAbstract(int $idProductAbstract, array $attributes): array
    {
        return isset($attributes[$idProductAbstract]) ? array_filter($attributes[$idProductAbstract]) : [];
    }
}
