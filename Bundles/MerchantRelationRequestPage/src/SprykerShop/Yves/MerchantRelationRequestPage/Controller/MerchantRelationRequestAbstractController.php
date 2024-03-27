<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Controller;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageFactory getFactory()
 */
class MerchantRelationRequestAbstractController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_MERCHANT_RELATION_REQUEST_UUID = 'uuid';

    /**
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->getFactory()->createCompanyUserReader()->getCurrentCompanyUser();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     *
     * @return void
     */
    protected function handleResponseErrors(
        MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
    ): void {
        foreach ($merchantRelationRequestCollectionResponseTransfer->getErrors() as $errorTransfer) {
            $this->addErrorMessage($errorTransfer->getMessageOrFail());
        }
    }
}
