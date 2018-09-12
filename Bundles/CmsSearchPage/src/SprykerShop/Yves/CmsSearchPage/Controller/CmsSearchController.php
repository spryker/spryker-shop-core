<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSearchPage\Controller;

use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CmsSearchPage\CmsSearchPageFactory getFactory()
 */
class CmsSearchController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function fulltextSearchAction(Request $request): View
    {
        $viewData = $this->executeFulltextSearchAction($request);

        return $this->view(
            $viewData,
            $this->getFactory()->getCmsSearchPageWidgetPlugins(),
            '@CmsSearchPage/views/search/search.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeFulltextSearchAction(Request $request): array
    {
        $searchString = $request->query->get('q', '');

        $searchResults = $this
            ->getFactory()
            ->getCmsPageSearchClient()
            ->search($searchString, $request->query->all());

        $searchResults['searchString'] = $searchString;

        return $searchResults;
    }
}
