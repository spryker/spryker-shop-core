<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Model;

use Spryker\Yves\Kernel\Application;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientInterface;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientInterface;
use SprykerShop\Yves\AgentPage\Plugin\Provider\AgentPageControllerProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AgentRedirectHandler implements AgentRedirectHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientInterface
     */
    protected $agentClient;

    /**
     * @var \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Yves\Kernel\Application
     */
    protected $application;

    /**
     * @param \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientInterface $agentClient
     * @param \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientInterface $customerClient
     * @param \Spryker\Yves\Kernel\Application $application
     */
    public function __construct(
        AgentPageToAgentClientInterface $agentClient,
        AgentPageToCustomerClientInterface $customerClient,
        Application $application
    ) {
        $this->agentClient = $agentClient;
        $this->customerClient = $customerClient;
        $this->application = $application;
    }

    /**
     * @api
     *
     * @inheritdoc
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     *
     * @return void
     */
    public function redirect(GetResponseEvent $event): void
    {
        if (!$this->agentClient->isLoggedIn()) {
            return;
        }

        if ($this->customerClient->isLoggedIn()) {
            return;
        }

        $this->redirectOnAccessDeniedException($event);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     *
     * @return void
     */
    protected function redirectOnAccessDeniedException(GetResponseEvent $event): void
    {
        $exception = $event->getException();

        if (!$exception instanceof AccessDeniedException) {
            return;
        }

        $response = new RedirectResponse(
            $this->application->url(AgentPageControllerProvider::ROUTE_LOGIN)
        );

        $event->setResponse($response);
    }
}
