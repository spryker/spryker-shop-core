<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Impersonator;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;

class CompanyUserImpersonator implements CompanyUserImpersonatorInterface
{
    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::PARAM_QUOTE_REQUEST_REFERENCE
     */
    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentAbstractController::PARAM_SWITCH_USER
     */
    protected const PARAM_SWITCH_USER = '_switch_user';

    protected const GLOSSARY_KEY_QUOTE_REQUEST_CONVERTED_TO_CART = 'quote_request_page.quote_request.converted_to_cart';

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|null $companyUserTransfer
     *
     * @return array
     */
    public function getImpersonationCompanyUserExitParams(QuoteRequestTransfer $quoteRequestTransfer, ?CompanyUserTransfer $companyUserTransfer): array
    {
        if ($companyUserTransfer && $companyUserTransfer->getIdCompanyUser() !== $quoteRequestTransfer->getCompanyUser()->getIdCompanyUser()) {
            return [
                static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestTransfer->getQuoteRequestReference(),
                static::PARAM_SWITCH_USER => '_exit',
            ];
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|null $companyUserTransfer
     *
     * @return array
     */
    public function getImpersonationCompanyUserEmailParams(QuoteRequestTransfer $quoteRequestTransfer, ?CompanyUserTransfer $companyUserTransfer): array
    {
        if (!$companyUserTransfer) {
            return [
                static::PARAM_SWITCH_USER => $quoteRequestTransfer->getCompanyUser()->getCustomer()->getEmail(),
            ];
        }

        return [];
    }
}
