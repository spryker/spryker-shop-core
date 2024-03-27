<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipPage\Form\Handler;

use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface MerchantRelationshipSearchHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $merchantRelationshipSearchForm
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer
     */
    public function handleSearchFormSubmit(
        Request $request,
        FormInterface $merchantRelationshipSearchForm
    ): MerchantRelationshipCollectionTransfer;
}
