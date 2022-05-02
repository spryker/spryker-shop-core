<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SecurityBlockerPage\EventSubscriber;

use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use SprykerShop\Yves\SecurityBlockerPage\Builder\MessageBuilderInterface;
use SprykerShop\Yves\SecurityBlockerPage\Dependency\Client\SecurityBlockerPageToSecurityBlockerClientInterface;
use SprykerShop\Yves\SecurityBlockerPage\SecurityBlockerPageConfig;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\AuthenticationEvents;

class SecurityBlockerCustomerEventSubscriber implements EventSubscriberInterface
{
    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\LoginForm::FORM_NAME
     *
     * @var string
     */
    protected const FORM_LOGIN_FORM = 'loginForm';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\LoginForm::FIELD_EMAIL
     *
     * @var string
     */
    protected const FORM_FIELD_EMAIL = 'email';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Formatter\LoginCheckUrlFormatter::ROUTE_CHECK_PATH
     *
     * @var string
     */
    protected const LOGIN_ROUTE = 'login_check';

    /**
     * @var int
     */
    protected const KERNEL_REQUEST_SUBSCRIBER_PRIORITY = 9;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_ACCOUNT_BLOCKED = 'security_blocker_page.error.account_blocked';

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected $requestStack;

    /**
     * @var \SprykerShop\Yves\SecurityBlockerPage\Dependency\Client\SecurityBlockerPageToSecurityBlockerClientInterface
     */
    protected $securityBlockerClient;

    /**
     * @var \SprykerShop\Yves\SecurityBlockerPage\Builder\MessageBuilderInterface
     */
    protected $messageBuilder;

    /**
     * @var \SprykerShop\Yves\SecurityBlockerPage\SecurityBlockerPageConfig
     */
    protected $securityBlockerPageConfig;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \SprykerShop\Yves\SecurityBlockerPage\Dependency\Client\SecurityBlockerPageToSecurityBlockerClientInterface $securityBlockerClient
     * @param \SprykerShop\Yves\SecurityBlockerPage\Builder\MessageBuilderInterface $messageBuilder
     * @param \SprykerShop\Yves\SecurityBlockerPage\SecurityBlockerPageConfig $securityBlockerPageConfig
     * @param string $localeName
     */
    public function __construct(
        RequestStack $requestStack,
        SecurityBlockerPageToSecurityBlockerClientInterface $securityBlockerClient,
        MessageBuilderInterface $messageBuilder,
        SecurityBlockerPageConfig $securityBlockerPageConfig,
        string $localeName
    ) {
        $this->requestStack = $requestStack;
        $this->securityBlockerClient = $securityBlockerClient;
        $this->messageBuilder = $messageBuilder;
        $this->securityBlockerPageConfig = $securityBlockerPageConfig;
        $this->localeName = $localeName;
    }

    /**
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
            KernelEvents::REQUEST => ['onKernelRequest', static::KERNEL_REQUEST_SUBSCRIBER_PRIORITY],
        ];
    }

    /**
     * @return void
     */
    public function onAuthenticationFailure(): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request || !$this->isLoginAttempt($request)) {
            return;
        }

        $securityCheckAuthContextTransfer = $this->createSecurityCheckAuthContextTransfer($request);

        $this->securityBlockerClient->incrementLoginAttemptCount($securityCheckAuthContextTransfer);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return void
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$this->isLoginAttempt($request)) {
            return;
        }

        $securityCheckAuthContextTransfer = $this->createSecurityCheckAuthContextTransfer($request);

        $securityCheckAuthResponseTransfer = $this->securityBlockerClient->isAccountBlocked($securityCheckAuthContextTransfer);

        if (!$securityCheckAuthResponseTransfer->getIsBlocked()) {
            return;
        }

        $exceptionMessage = $this->messageBuilder->getExceptionMessage($securityCheckAuthResponseTransfer, $this->localeName);

        throw new HttpException(Response::HTTP_TOO_MANY_REQUESTS, $exceptionMessage);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isLoginAttempt(Request $request): bool
    {
        $currentRoute = $request->attributes->get('_route');
        if ($this->securityBlockerPageConfig->isLocaleInCustomerLoginCheckPath()) {
            $currentRoute = mb_substr($currentRoute, mb_strlen($this->localeName) - 2);
        }

        return $currentRoute === static::LOGIN_ROUTE && $request->getMethod() === Request::METHOD_POST;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer
     */
    protected function createSecurityCheckAuthContextTransfer(Request $request): SecurityCheckAuthContextTransfer
    {
        return (new SecurityCheckAuthContextTransfer())
            ->setType(SecurityBlockerPageConfig::SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE)
            ->setAccount($request->get(static::FORM_LOGIN_FORM)[static::FORM_FIELD_EMAIL] ?? '')
            ->setIp($request->getClientIp());
    }
}
