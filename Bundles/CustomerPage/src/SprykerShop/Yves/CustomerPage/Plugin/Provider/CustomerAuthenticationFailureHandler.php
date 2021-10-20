<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Provider;

use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\CustomerPage\Exception\NotConfirmedAccountException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

/**
 * @method \Spryker\Client\Customer\CustomerClientInterface getClient()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerAuthenticationFailureHandler extends BaseCustomerAuthenticationHandler implements AuthenticationFailureHandlerInterface
{
    /**
     * @var string
     */
    public const MESSAGE_CUSTOMER_AUTHENTICATION_FAILED = 'customer.authentication.failed';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CUSTOMER_NOT_CONFIRMED_ACCOUNT = 'customer.authorization.invalid_account';

    /**
     * @var \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected $flashMessenger;

    /**
     * @var string|null
     */
    protected $targetUrl;

    /**
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     * @param string|null $targetUrl
     */
    public function __construct(FlashMessengerInterface $flashMessenger, ?string $targetUrl = null)
    {
        $this->flashMessenger = $flashMessenger;
        $this->targetUrl = $targetUrl;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $this->flashMessenger->addErrorMessage($this->buildErrorMessage($exception));

        return $this->createRefererRedirectResponse($request, $this->targetUrl);
    }

    /**
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     *
     * @return string
     */
    protected function buildErrorMessage(AuthenticationException $exception): string
    {
        if ($exception instanceof NotConfirmedAccountException) {
            return static::GLOSSARY_KEY_CUSTOMER_NOT_CONFIRMED_ACCOUNT;
        }

        return static::MESSAGE_CUSTOMER_AUTHENTICATION_FAILED;
    }
}
