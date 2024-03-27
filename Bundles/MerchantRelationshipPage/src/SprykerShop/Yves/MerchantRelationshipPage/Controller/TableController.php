<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipPage\Controller;

use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\MerchantRelationshipPage\MerchantRelationshipPageConfig getConfig()
 * @method \SprykerShop\Yves\MerchantRelationshipPage\MerchantRelationshipPageFactory getFactory()
 */
class TableController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request): View
    {
        return $this->view(
            $this->executeIndexAction($request),
            [],
            '@MerchantRelationshipPage/views/table/table.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    protected function executeIndexAction(Request $request): array
    {
        $merchantRelationshipSearchForm = $this->getFactory()->getMerchantRelationshipSearchForm();
        $merchantRelationshipCollectionTransfer = $this->getFactory()
            ->createMerchantRelationshipSearchHandler()
            ->handleSearchFormSubmit($request, $merchantRelationshipSearchForm);

        return [
            'merchantRelationships' => $merchantRelationshipCollectionTransfer->getMerchantRelationships(),
            'pagination' => $merchantRelationshipCollectionTransfer->getPagination(),
            'merchantRelationshipSearchForm' => $merchantRelationshipSearchForm->createView(),
        ];
    }
}
