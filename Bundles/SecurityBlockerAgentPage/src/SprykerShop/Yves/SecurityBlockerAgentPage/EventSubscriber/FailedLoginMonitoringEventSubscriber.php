<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SecurityBlockerAgentPage\EventSubscriber;

use Generated\Shared\Transfer\AuthContextTransfer;
use Spryker\Yves\Router\Router\RouterInterface;
use SprykerShop\Yves\SecurityBlockerAgentPage\Dependency\Client\SecurityBlockerAgentPageToSecurityBlockerClientInterface;
use SprykerShop\Yves\SecurityBlockerAgentPage\SecurityBlockerAgentPageConfig;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\AuthenticationEvents;

class FailedLoginMonitoringEventSubscriber implements EventSubscriberInterface
{
    protected const FORM_LOGIN_FORM = 'loginForm';
    protected const FORM_FIELD_EMAIL = 'email';
    protected const LOGIN_ROUTE = 'agent/check_login';

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected $requestStack;

    /**
     * @var \SprykerShop\Yves\SecurityBlockerAgentPage\Dependency\Client\SecurityBlockerAgentPageToSecurityBlockerClientInterface
     */
    protected $securityBlockerClient;

    /**
     * @var \Spryker\Yves\Router\Router\RouterInterface
     */
    protected $router;

    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \Spryker\Yves\Router\Router\RouterInterface $router
     * @param \SprykerShop\Yves\SecurityBlockerAgentPage\Dependency\Client\SecurityBlockerAgentPageToSecurityBlockerClientInterface $securityBlockerClient
     */
    public function __construct(
        RequestStack $requestStack,
        RouterInterface $router,
        SecurityBlockerAgentPageToSecurityBlockerClientInterface $securityBlockerClient
    ) {
        $this->requestStack = $requestStack;
        $this->router = $router;
        $this->securityBlockerClient = $securityBlockerClient;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
            KernelEvents::REQUEST => ['onKernelRequest', 9],
        ];
    }

    /**
     * @return void
     */
    public function onAuthenticationFailure(): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request || $request->attributes->get('_route') !== 'agent/login_check') {
            return;
        }

        $authContextTransfer = (new AuthContextTransfer())
            ->setTtl(60)
            ->setAccount($request->get(static::FORM_LOGIN_FORM)[static::FORM_FIELD_EMAIL] ?? '')
            ->setIp($request->getClientIp());

        $this->securityBlockerClient->incrementLoginAttempt($authContextTransfer);
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

        if ($request->attributes->get('_route') !== 'agent_login_check' || $request->getMethod() !== Request::METHOD_POST) {
            return;
        }

        $account = $request->get(static::FORM_LOGIN_FORM)[static::FORM_FIELD_EMAIL] ?? '';
        $ip = $request->getClientIp();
        $authContextTransfer = (new AuthContextTransfer())
            ->setType(SecurityBlockerAgentPageConfig::SECURITY_BLOCKER_ENTITY_TYPE)
            ->setAccount($account)
            ->setIp($ip);

        $authResponseTransfer = $this->securityBlockerClient->getLoginAttempt($authContextTransfer);

        if ($authResponseTransfer->getIsSuccessful()) {
            return;
        }

        throw new HttpException(Response::HTTP_TOO_MANY_REQUESTS, sprintf('Agent %s is blocked on IP %s.', $account, $ip));
    }
}
