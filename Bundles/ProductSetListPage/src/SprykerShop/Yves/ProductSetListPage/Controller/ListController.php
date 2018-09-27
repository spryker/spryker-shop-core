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
    public const PARAM_LIMIT = 'limit';
    public const PARAM_OFFSET = 'offset';
    public const DEFAULT_LIMIT = 100;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request)
    {
        $viewData = $this->executeIndexAction($request);

        return $this->view(
            $viewData,
            $this->getFactory()->getProductSetListPageWidgets(),
            '@ProductSetListPage/views/set-list/set-list.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeIndexAction(Request $request): array
    {
        $limit = $request->query->getInt(static::PARAM_LIMIT, self::DEFAULT_LIMIT);
        $offset = $request->query->get(static::PARAM_OFFSET);

        return $this->getFactory()
            ->getProductSetPageSearchClient()
            ->getProductSetList($limit, $offset);
    }
}
