<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Form\Handler;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface MerchantRelationRequestSearchHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $merchantRelationRequestSearchForm
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function handleSearchFormSubmit(
        Request $request,
        FormInterface $merchantRelationRequestSearchForm
    ): MerchantRelationRequestCollectionTransfer;
}
