<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CatalogPage\CatalogPageFactory getFactory()
 */
class SuggestionController extends AbstractController
{
    /**
     * @var string
     */
    public const PARAM_SEARCH_QUERY = 'q';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request)
    {
        $searchString = (string)$request->query->get(static::PARAM_SEARCH_QUERY);
        if (!$searchString) {
            return $this->jsonResponse();
        }

        $shopContextParameters = $this->getFactory()
            ->getShopContext()
            ->modifiedToArray();
        $parameters = array_merge($request->query->all(), $shopContextParameters);

        $searchResults = $this
            ->getFactory()
            ->getCatalogClient()
            ->catalogSuggestSearch($searchString, $parameters);

        return $this->jsonResponse([
            'completion' => ($searchResults['completion'][0] ?? null),
            'suggestion' => $this->renderView('@CatalogPage/views/suggestion-results/suggestion-results.twig', $searchResults)->getContent(),
        ]);
    }
}
