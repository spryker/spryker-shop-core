<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\FacetFilter;

use ArrayObject;

class CategoryNodeWalker
{
    /**
     * @var int
     */
    protected $idCategoryNode;

    /**
     * @var int[]
     */
    protected $quantities;

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categories
     * @param int $idCategory
     * @param int[] $quantities
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryNodeStorageTransfer[]
     */
    public function start(ArrayObject $categories, int $idCategory, array $quantities): ArrayObject
    {
        $this->idCategoryNode = $idCategory;
        $this->quantities = $quantities;

        $active = false;
        $childQuantity = 0;

        return $this->walk($categories, $active, $childQuantity);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categoryTransferCollection
     * @param bool $active
     * @param int $childQuantity
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryNodeStorageTransfer[]
     */
    protected function walk(ArrayObject $categoryTransferCollection, &$active, &$childQuantity): ArrayObject
    {
        $filteredCategoryTransferCollection = new ArrayObject();

        foreach ($categoryTransferCollection as $categoryNodeStorageTransfer) {
            $categoryNodeStorageTransfer->setIsActive(false);
            $categoryNodeStorageTransfer->setIsCurrent(false);

            $children = $categoryNodeStorageTransfer->getChildren();
            $quantity = $this->quantities[$categoryNodeStorageTransfer->getNodeId()] ?? 0;

            $childQuantity += $quantity;

            $categoryNodeStorageTransfer->setQuantity($quantity);
            $categoryNodeStorageTransfer->setHasChildren(count($children) > 0);

            if ($categoryNodeStorageTransfer->getNodeId() === $this->idCategoryNode) {
                $categoryNodeStorageTransfer->setIsActive(true);
                $categoryNodeStorageTransfer->setIsCurrent(true);

                $active = true;
            }

            if ($categoryNodeStorageTransfer->getHasChildren()) {
                $categoryNodeStorageTransfer->setChildren($this->walk($children, $active, $quantity));
            }

            $filteredCategoryTransferCollection->append($categoryNodeStorageTransfer);
        }

        return $filteredCategoryTransferCollection;
    }
}
