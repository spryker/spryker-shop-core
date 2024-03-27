<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Controller;

use SprykerShop\Yves\MerchantRelationRequestPage\Plugin\Router\MerchantRelationRequestPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageFactory getFactory()
 */
class MerchantRelationRequestCancelController extends MerchantRelationRequestAbstractController
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MERCHANT_RELATION_REQUEST_SUCCESS_CANCELED = 'merchant_relation_request_page.merchant_relation_request.success.canceled';

    /**
     * @param string $uuid
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cancelAction(string $uuid): RedirectResponse
    {
        $merchantRelationRequestResponseTransfer = $this->getFactory()
            ->createMerchantRelationRequestHandler()
            ->cancelMerchantRelationRequest($uuid);

        if (!$merchantRelationRequestResponseTransfer->getErrors()->count()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_MERCHANT_RELATION_REQUEST_SUCCESS_CANCELED);
        }

        $this->handleResponseErrors($merchantRelationRequestResponseTransfer);

        return $this->redirectResponseInternal(
            MerchantRelationRequestPageRouteProviderPlugin::ROUTE_NAME_MERCHANT_RELATION_REQUEST,
        );
    }
}
