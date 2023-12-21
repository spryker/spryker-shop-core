<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionAgentValidation\EventSubscriber;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use Generated\Shared\Transfer\UserTransfer;
use SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToAgentClientInterface;
use SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationConfig;
use SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentSaverPluginInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class SaveAgentSessionEventSubscriber implements EventSubscriberInterface
{
    /**
     * @uses \SprykerShop\Yves\AgentPage\Plugin\Security\AgentPageSecurityPlugin::ROLE_AGENT
     *
     * @var string
     */
    protected const ROLE_AGENT = 'ROLE_AGENT';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE
     *
     * @var string
     */
    protected const COL_STATUS_ACTIVE = 'active';

    /**
     * @var \SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToAgentClientInterface
     */
    protected SessionAgentValidationToAgentClientInterface $agentClient;

    /**
     * @var \SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentSaverPluginInterface
     */
    protected SessionAgentSaverPluginInterface $sessionAgentSaverPlugin;

    /**
     * @var \SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationConfig
     */
    protected SessionAgentValidationConfig $config;

    /**
     * @param \SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToAgentClientInterface $agentClient
     * @param \SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentSaverPluginInterface $sessionAgentSaverPlugin
     * @param \SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationConfig $config
     */
    public function __construct(
        SessionAgentValidationToAgentClientInterface $agentClient,
        SessionAgentSaverPluginInterface $sessionAgentSaverPlugin,
        SessionAgentValidationConfig $config
    ) {
        $this->agentClient = $agentClient;
        $this->sessionAgentSaverPlugin = $sessionAgentSaverPlugin;
        $this->config = $config;
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        /** @deprecated Exists for Symfony 5 support only. */
        if (!class_exists(LoginSuccessEvent::class)) {
            return [
                SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
            ];
        }

        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }

    /**
     * @param \Symfony\Component\Security\Http\Event\InteractiveLoginEvent $event
     *
     * @return void
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $this->saveSession($event->getRequest(), $event->getAuthenticationToken()->getUser());
    }

    /**
     * @param \Symfony\Component\Security\Http\Event\LoginSuccessEvent $event
     *
     * @return void
     */
    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $this->saveSession($event->getRequest(), $event->getUser());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\User\UserInterface|null $user
     *
     * @return void
     */
    protected function saveSession(Request $request, ?UserInterface $user): void
    {
        if (!$request->hasSession()) {
            return;
        }

        if (!$user instanceof UserInterface || !$this->hasAgentRole($user)) {
            return;
        }

        $userTransfer = $this->agentClient->findAgentByUsername(
            (new UserTransfer())->setUsername($this->getUserIdentifier($user)),
        );

        if ($userTransfer === null || !$userTransfer->getIdUser() || $userTransfer->getStatus() !== static::COL_STATUS_ACTIVE) {
            return;
        }

        $this->sessionAgentSaverPlugin->saveSession(
            (new SessionEntityRequestTransfer())
                ->setIdEntity($userTransfer->getIdUserOrFail())
                ->setIdSession($request->getSession()->getId())
                ->setEntityType($this->config->getSessionEntityType()),
        );
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return bool
     */
    protected function hasAgentRole(UserInterface $user): bool
    {
        return in_array(static::ROLE_AGENT, $user->getRoles(), true);
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return string
     */
    protected function getUserIdentifier(UserInterface $user): string
    {
        if ($this->isSymfonyVersion5() === true) {
            return $user->getUsername();
        }

        return $user->getUserIdentifier();
    }

    /**
     * @deprecated Shim for Symfony Security Core 5.x, to be removed when Symfony Security Core dependency becomes 6.x+.
     *
     * @return bool
     */
    protected function isSymfonyVersion5(): bool
    {
        return class_exists(AuthenticationProviderManager::class);
    }
}
