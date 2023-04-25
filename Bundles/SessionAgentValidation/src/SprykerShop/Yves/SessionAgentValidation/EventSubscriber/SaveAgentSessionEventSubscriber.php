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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
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
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
        ];
    }

    /**
     * @param \Symfony\Component\Security\Http\Event\InteractiveLoginEvent $event
     *
     * @return void
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->hasSession()) {
            return;
        }

        $user = $event->getAuthenticationToken()->getUser();
        if (!$user instanceof UserInterface || !$this->hasAgentRole($user)) {
            return;
        }

        $userTransfer = $this->agentClient->findAgentByUsername(
            (new UserTransfer())->setUsername($user->getUsername()),
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
}
