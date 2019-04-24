<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestAbstractController extends AbstractController
{
    /**
     * @see \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST
     */
    protected const ROUTE_QUOTE_REQUEST = 'quote-request';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::PARAM_QUOTE_REQUEST_REFERENCE
     */
    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST_EDIT
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT = 'quote-request/edit';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST_DETAILS
     */
    protected const ROUTE_QUOTE_REQUEST_DETAILS = 'quote-request/details';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST_SEND_TO_USER
     */
    protected const ROUTE_QUOTE_REQUEST_SEND_TO_USER = 'quote-request/send-to-user';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST_EDIT_ITEMS_CONFIRM
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT_ITEMS_CONFIRM = 'quote-request/edit-items-confirm';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST_EDIT_ITEMS
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT_ITEMS = 'quote-request/edit-items';

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::ROUTE_CART
     */
    protected const ROUTE_CART = 'cart';

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->findCompanyUser();

        if ($companyUserTransfer === null) {
            throw new NotFoundHttpException("Only company users are allowed to access this page");
        }
    }

    /**
     * @param string $quoteRequestReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function getCompanyUserQuoteRequestByReference(string $quoteRequestReference): QuoteRequestTransfer
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestReference)
            ->setIdCompanyUser($this->getFactory()->getCompanyUserClient()->findCompanyUser()->getIdCompanyUser());

        $quoteRequestResponseTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->getQuoteRequest($quoteRequestFilterTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            throw new NotFoundHttpException();
        }

        return $quoteRequestResponseTransfer->getQuoteRequest();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer
     *
     * @return void
     */
    protected function handleResponseErrors(QuoteRequestResponseTransfer $quoteRequestResponseTransfer): void
    {
        foreach ($quoteRequestResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }
}
