<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetListPage\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductSetListPage\ProductSetListPageFactory getFactory()
 */
class ListController extends AbstractController
{
    const PARAM_LIMIT = 'limit';
    const PARAM_OFFSET = 'offset';
    const DEFAULT_LIMIT = 100;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $limit = $request->query->getInt(static::PARAM_LIMIT, self::DEFAULT_LIMIT);
        $offset = $request->query->get(static::PARAM_OFFSET);

        $searchResults = $this->getFactory()
            ->getProductSetPageSearchClient()
            ->getProductSetList($limit, $offset);

        return $this->view($searchResults, $this->getFactory()->getProductSetListPageWidgets());
    }
}
