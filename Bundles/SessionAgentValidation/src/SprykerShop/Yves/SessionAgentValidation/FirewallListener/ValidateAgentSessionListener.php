<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionAgentValidation\FirewallListener;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToAgentClientInterface;
use SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationConfig;
use SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentValidatorPluginInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Firewall\AbstractListener;

class ValidateAgentSessionListener extends AbstractListener implements ValidateAgentSessionListenerInterface
{
    /**
     * @see \SprykerShop\Yves\AgentPage\Plugin\Router\AgentPageRouteProviderPlugin::ROUTE_NAME_LOGIN
     *
     * @var string
     */
    protected const LOGIN_PATH = '/agent/login';

    /**
     * @var \SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToAgentClientInterface
     */
    protected SessionAgentValidationToAgentClientInterface $agentClient;

    /**
     * @var \SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationConfig
     */
    protected SessionAgentValidationConfig $config;

    /**
     * @var \SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentValidatorPluginInterface
     */
    protected SessionAgentValidatorPluginInterface $sessionAgentValidatorPlugin;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface|null
     */
    protected ?TokenStorageInterface $tokenStorage;

    /**
     * @param \SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToAgentClientInterface $agentClient
     * @param \SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentValidatorPluginInterface $sessionAgentValidatorPlugin
     * @param \SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationConfig $config
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface|null $tokenStorage
     */
    public function __construct(
        SessionAgentValidationToAgentClientInterface $agentClient,
        SessionAgentValidatorPluginInterface $sessionAgentValidatorPlugin,
        SessionAgentValidationConfig $config,
        ?TokenStorageInterface $tokenStorage = null
    ) {
        $this->agentClient = $agentClient;
        $this->sessionAgentValidatorPlugin = $sessionAgentValidatorPlugin;
        $this->config = $config;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool|null
     */
    public function supports(Request $request): ?bool
    {
        return null;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @return void
     */
    public function authenticate(RequestEvent $event): void
    {
        if (!$this->agentClient->isLoggedIn()) {
            return;
        }

        $currentAgent = $this->agentClient->getAgent();
        if (!$currentAgent->getIdUser()) {
            return;
        }

        $session = $event->getRequest()->getSession();

        $sessionAgentTransfer = (new SessionEntityRequestTransfer())
            ->setIdEntity($currentAgent->getIdUserOrFail())
            ->setIdSession($session->getId())
            ->setEntityType($this->config->getSessionEntityType());

        $sessionEntityResponseTransfer = $this->sessionAgentValidatorPlugin->validate($sessionAgentTransfer);
        if ($sessionEntityResponseTransfer->getIsSuccessfull()) {
            return;
        }

        $session->invalidate(0);
        $event->setResponse(new RedirectResponse(static::LOGIN_PATH));

        if ($this->tokenStorage !== null) {
            $this->tokenStorage->setToken(null);
        }
    }

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     *
     * @return void
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage): void
    {
        $this->tokenStorage = $tokenStorage;
    }
}
