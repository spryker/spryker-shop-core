<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Plugin\Twig;

use ArrayObject;
use Generated\Shared\Transfer\FacetSearchResultTransfer;
use Spryker\Shared\Twig\TwigExtension;
use SprykerShop\Yves\CatalogPage\FacetFilter\CategoryNodeWalker;
use Twig\TwigFunction;

class CategoryFilterTwigExtension extends TwigExtension
{
    public const FUNCTION_PREPARE_FILTER_CATEGORIES = 'prepareFilterCategories';

    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(static::FUNCTION_PREPARE_FILTER_CATEGORIES, [$this, static::FUNCTION_PREPARE_FILTER_CATEGORIES], [
                'needs_context' => true,
            ]),
        ];
    }

    /**
     * @param array $context
     * @param \Generated\Shared\Transfer\FacetSearchResultTransfer $searchResultFacet
     * @param mixed $idCategoryNode
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryNodeStorageTransfer[]
     */
    public function prepareFilterCategories(array $context, FacetSearchResultTransfer $searchResultFacet, $idCategoryNode): ArrayObject
    {
        $categories = $this->getCategoriesFromContext($context);
        $quantities = $this->getQuantitiesFromSearchResult($searchResultFacet);

        $categoryNodeWalker = new CategoryNodeWalker();

        return $categoryNodeWalker->start($categories, (int)$idCategoryNode, $quantities);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetSearchResultTransfer $searchResultFacet
     *
     * @return int[]
     */
    protected function getQuantitiesFromSearchResult(FacetSearchResultTransfer $searchResultFacet): array
    {
        $quantities = [];
        foreach ($searchResultFacet->getValues() as $searchResultValue) {
            $quantities[$searchResultValue->getValue()] = $searchResultValue->getDocCount();
        }

        return $quantities;
    }

    /**
     * @param array $context
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryNodeStorageTransfer[]
     */
    protected function getCategoriesFromContext(array $context): ArrayObject
    {
        return $context['categories'] ?? new ArrayObject();
    }
}
