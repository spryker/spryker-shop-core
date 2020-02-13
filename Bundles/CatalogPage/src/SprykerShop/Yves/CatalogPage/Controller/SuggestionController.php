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
    public const PARAM_SEARCH_QUERY = 'q';
    public const MERCHANT_REFERENCE = 'merchant_reference';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request)
    {
        $searchString = $request->query->get(self::PARAM_SEARCH_QUERY);

        if (empty($searchString)) {
            return $this->jsonResponse();
        }

        $shopContextTransfer = $this->getFactory()->getShopContext();
        $shopContextParameters = $shopContextTransfer->modifiedToArray(true, false);
        $parameters = array_merge($request->query->all(), $shopContextParameters);

        $searchResults = $this
            ->getFactory()
            ->getCatalogClient()
            ->catalogSuggestSearch($searchString, $parameters);

        return $this->jsonResponse([
            'completion' => ($searchResults['completion'] ? $searchResults['completion'][0] : null),
            'suggestion' => $this->renderView('@CatalogPage/views/suggestion-results/suggestion-results.twig', $searchResults)->getContent(),
        ]);
    }
}
