<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionAgentValidation;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToAgentClientInterface;
use SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToSessionClientInterface;
use SprykerShop\Yves\SessionAgentValidation\EventSubscriber\SaveAgentSessionEventSubscriber;
use SprykerShop\Yves\SessionAgentValidation\Expander\SecurityAuthenticationListenerFactoryTypeExpander;
use SprykerShop\Yves\SessionAgentValidation\Expander\SecurityAuthenticationListenerFactoryTypeExpanderInterface;
use SprykerShop\Yves\SessionAgentValidation\Extender\SessionAgentValidationSecurityExtender;
use SprykerShop\Yves\SessionAgentValidation\Extender\SessionAgentValidationSecurityExtenderInterface;
use SprykerShop\Yves\SessionAgentValidation\FirewallListener\ValidateAgentSessionListener;
use SprykerShop\Yves\SessionAgentValidation\FirewallListener\ValidateAgentSessionListenerInterface;
use SprykerShop\Yves\SessionAgentValidation\Updater\SessionAgentValidationSessionUpdater;
use SprykerShop\Yves\SessionAgentValidation\Updater\SessionAgentValidationSessionUpdaterInterface;
use SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentSaverPluginInterface;
use SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentValidatorPluginInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @method \SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationConfig getConfig()
 */
class SessionAgentValidationFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createSaveAgentSessionEventSubscriber(): EventSubscriberInterface
    {
        return new SaveAgentSessionEventSubscriber(
            $this->getAgentClient(),
            $this->getSessionAgentSaverPlugin(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerShop\Yves\SessionAgentValidation\FirewallListener\ValidateAgentSessionListenerInterface
     */
    public function createValidateAgentSessionListener(): ValidateAgentSessionListenerInterface
    {
        return new ValidateAgentSessionListener(
            $this->getAgentClient(),
            $this->getSessionAgentValidatorPlugin(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerShop\Yves\SessionAgentValidation\Extender\SessionAgentValidationSecurityExtenderInterface
     */
    public function createSessionAgentValidationSecurityExtender(): SessionAgentValidationSecurityExtenderInterface
    {
        return new SessionAgentValidationSecurityExtender(
            $this->createValidateAgentSessionListener(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerShop\Yves\SessionAgentValidation\Updater\SessionAgentValidationSessionUpdaterInterface
     */
    public function createSessionAgentValidationSessionUpdater(): SessionAgentValidationSessionUpdaterInterface
    {
        return new SessionAgentValidationSessionUpdater(
            $this->getSecurityAuthorizationChecker(),
            $this->getAgentClient(),
            $this->getSessionClient(),
            $this->getSessionAgentSaverPlugin(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerShop\Yves\SessionAgentValidation\Expander\SecurityAuthenticationListenerFactoryTypeExpanderInterface
     */
    public function createSecurityAuthenticationListenerFactoryTypeExpander(): SecurityAuthenticationListenerFactoryTypeExpanderInterface
    {
        return new SecurityAuthenticationListenerFactoryTypeExpander($this->getConfig());
    }

    /**
     * @return \SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentSaverPluginInterface
     */
    public function getSessionAgentSaverPlugin(): SessionAgentSaverPluginInterface
    {
        return $this->getProvidedDependency(SessionAgentValidationDependencyProvider::PLUGIN_SESSION_AGENT_SAVER);
    }

    /**
     * @return \SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentValidatorPluginInterface
     */
    public function getSessionAgentValidatorPlugin(): SessionAgentValidatorPluginInterface
    {
        return $this->getProvidedDependency(SessionAgentValidationDependencyProvider::PLUGIN_SESSION_AGENT_VALIDATOR);
    }

    /**
     * @return \SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToAgentClientInterface
     */
    public function getAgentClient(): SessionAgentValidationToAgentClientInterface
    {
        return $this->getProvidedDependency(SessionAgentValidationDependencyProvider::CLIENT_AGENT);
    }

    /**
     * @return \SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToSessionClientInterface
     */
    public function getSessionClient(): SessionAgentValidationToSessionClientInterface
    {
        return $this->getProvidedDependency(SessionAgentValidationDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    public function getSecurityAuthorizationChecker(): AuthorizationCheckerInterface
    {
        return $this->getProvidedDependency(SessionAgentValidationDependencyProvider::SERVICE_SECURITY_AUTHORIZATION_CHECKER);
    }
}
