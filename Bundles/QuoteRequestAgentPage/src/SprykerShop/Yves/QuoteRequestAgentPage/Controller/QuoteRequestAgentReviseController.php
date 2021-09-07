<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Controller;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentPage\QuoteRequestAgentPageFactory getFactory()
 */
class QuoteRequestAgentReviseController extends QuoteRequestAgentAbstractController
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_VERSION_CREATED = 'quote_request_page.quote_request_version.created';

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(string $quoteRequestReference): RedirectResponse
    {
        $response = $this->executeIndexAction($quoteRequestReference);

        return $response;
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(string $quoteRequestReference): RedirectResponse
    {
        $quoteRequestResponseTransfer = $this->getFactory()
            ->getQuoteRequestAgentClient()
            ->reviseQuoteRequest((new QuoteRequestFilterTransfer())->setQuoteRequestReference($quoteRequestReference));

        if ($quoteRequestResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_VERSION_CREATED);
        }

        $this->handleResponseErrors($quoteRequestResponseTransfer);

        return $this->redirectResponseInternal(QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_EDIT, [
            static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference,
        ]);
    }
}
