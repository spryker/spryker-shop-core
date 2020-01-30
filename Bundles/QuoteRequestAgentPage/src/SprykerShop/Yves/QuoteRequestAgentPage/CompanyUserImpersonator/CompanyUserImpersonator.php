<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\CompanyUserImpersonator;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToCompanyUserClientInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToMessengerClientInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteClientInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestAgentClientInterface;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CompanyUserImpersonator implements CompanyUserImpersonatorInterface
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_CONVERTED_TO_CART = 'quote_request_page.quote_request.converted_to_cart';
    protected const PARAM_SWITCH_USER = '_switch_user';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::PARAM_QUOTE_REQUEST_REFERENCE
     */
    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_QUOTE_REQUEST_AGENT_EDIT_ITEMS
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_EDIT_ITEMS = 'agent/quote-request/edit-items';

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToMessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @var \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    protected $router;

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestAgentClientInterface
     */
    protected $quoteRequestAgentClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToCompanyUserClientInterface
     */
    protected $companyUserClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToMessengerClientInterface $messengerClient
     * @param \Symfony\Cmf\Component\Routing\ChainRouterInterface $router
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestAgentClientInterface $quoteRequestAgentClient
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToCompanyUserClientInterface $companyUserClient
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteClientInterface $quoteClient
     */
    public function __construct(
        QuoteRequestAgentPageToMessengerClientInterface $messengerClient,
        ChainRouterInterface $router,
        QuoteRequestAgentPageToQuoteRequestAgentClientInterface $quoteRequestAgentClient,
        QuoteRequestAgentPageToCompanyUserClientInterface $companyUserClient,
        QuoteRequestAgentPageToQuoteClientInterface $quoteClient
    ) {
        $this->messengerClient = $messengerClient;
        $this->router = $router;
        $this->quoteRequestAgentClient = $quoteRequestAgentClient;
        $this->companyUserClient = $companyUserClient;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param string $routeToRedirect
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function impersonateCompanyUser(QuoteRequestTransfer $quoteRequestTransfer, string $routeToRedirect): RedirectResponse
    {
        $quoteTransfer = $this->quoteClient->getQuote();
        $redirectResponse = $this->checkCompanyUserImpersonation($quoteRequestTransfer, $quoteTransfer, $routeToRedirect);

        if ($redirectResponse) {
            return $redirectResponse;
        }

        $quoteResponseTransfer = $this->quoteRequestAgentClient->convertQuoteRequestToQuote($quoteRequestTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->messengerClient->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_CONVERTED_TO_CART);
        }

        $this->handleQuoteResponseErrors($quoteResponseTransfer);

        $companyUserTransfer = $this->companyUserClient->findCompanyUser();

        if (!$companyUserTransfer) {
            return $this->redirectResponseInternal($routeToRedirect, [
                static::PARAM_SWITCH_USER => $quoteRequestTransfer->getCompanyUser()->getCustomer()->getEmail(),
            ]);
        }

        return $this->redirectResponseInternal($routeToRedirect);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $routeToRedirect
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|null
     */
    protected function checkCompanyUserImpersonation(QuoteRequestTransfer $quoteRequestTransfer, QuoteTransfer $quoteTransfer, string $routeToRedirect): ?RedirectResponse
    {
        $companyUserTransfer = $this->companyUserClient->findCompanyUser();

        if ($companyUserTransfer && $companyUserTransfer->getIdCompanyUser() !== $quoteRequestTransfer->getCompanyUser()->getIdCompanyUser()) {
            return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_AGENT_EDIT_ITEMS, [
                static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestTransfer->getQuoteRequestReference(),
                static::PARAM_SWITCH_USER => '_exit',
            ]);
        }

        if ($quoteTransfer->getQuoteRequestReference() === $quoteRequestTransfer->getQuoteRequestReference()) {
            return $this->redirectResponseInternal($routeToRedirect);
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return void
     */
    protected function handleQuoteResponseErrors(QuoteResponseTransfer $quoteResponseTransfer): void
    {
        foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
            $this->messengerClient->addErrorMessage($quoteErrorTransfer->getMessage());
        }
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param int $code
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectResponseInternal($path, $parameters = [], $code = 302): RedirectResponse
    {
        return new RedirectResponse($this->router->generate($path, $parameters), $code);
    }
}
