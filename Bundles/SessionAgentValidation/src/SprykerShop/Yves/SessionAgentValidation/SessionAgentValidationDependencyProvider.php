<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionAgentValidation;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToAgentClientBridge;
use SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToSessionClientBridge;
use SprykerShop\Yves\SessionAgentValidation\Exception\MissingSessionAgentSaverPluginException;
use SprykerShop\Yves\SessionAgentValidation\Exception\MissingSessionAgentValidatorPluginException;
use SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentSaverPluginInterface;
use SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentValidatorPluginInterface;

/**
 * @method \SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationConfig getConfig()
 */
class SessionAgentValidationDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGIN_SESSION_AGENT_SAVER = 'PLUGIN_SESSION_AGENT_SAVER';

    /**
     * @var string
     */
    public const PLUGIN_SESSION_AGENT_VALIDATOR = 'PLUGIN_SESSION_AGENT_VALIDATOR';

    /**
     * @var string
     */
    public const CLIENT_AGENT = 'CLIENT_AGENT';

    /**
     * @var string
     */
    public const CLIENT_SESSION = 'CLIENT_SESSION';

    /**
     * @uses \Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_AUTHORIZATION_CHECKER
     *
     * @var string
     */
    public const SERVICE_SECURITY_AUTHORIZATION_CHECKER = 'security.authorization_checker';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addSessionAgentSaverPlugin($container);
        $container = $this->addSessionAgentValidatorPlugin($container);
        $container = $this->addAgentClient($container);
        $container = $this->addSessionClient($container);
        $container = $this->addSecurityAuthorizationCheckerService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionAgentSaverPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_SESSION_AGENT_SAVER, function () {
            return $this->getSessionAgentSaverPlugin();
        });

        return $container;
    }

    /**
     * @throws \SprykerShop\Yves\SessionAgentValidation\Exception\MissingSessionAgentSaverPluginException
     *
     * @return \SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentSaverPluginInterface
     */
    protected function getSessionAgentSaverPlugin(): SessionAgentSaverPluginInterface
    {
        throw new MissingSessionAgentSaverPluginException(
            sprintf(
                'Missing instance of %s! You need to configure SessionAgentSaverPlugin ' .
                'in your own %s::%s() ' .
                'to be able to save session for agent.',
                SessionAgentSaverPluginInterface::class,
                static::class,
                __METHOD__,
            ),
        );
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionAgentValidatorPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_SESSION_AGENT_VALIDATOR, function () {
            return $this->getSessionAgentValidatorPlugin();
        });

        return $container;
    }

    /**
     * @throws \SprykerShop\Yves\SessionAgentValidation\Exception\MissingSessionAgentValidatorPluginException
     *
     * @return \SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentValidatorPluginInterface
     */
    protected function getSessionAgentValidatorPlugin(): SessionAgentValidatorPluginInterface
    {
        throw new MissingSessionAgentValidatorPluginException(
            sprintf(
                'Missing instance of %s! You need to configure SessionAgentValidatorPlugin ' .
                'in your own %s::%s() ' .
                'to be able to validate session for agent.',
                SessionAgentValidatorPluginInterface::class,
                static::class,
                __METHOD__,
            ),
        );
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAgentClient(Container $container): Container
    {
        $container->set(static::CLIENT_AGENT, function (Container $container) {
            return new SessionAgentValidationToAgentClientBridge(
                $container->getLocator()->agent()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionClient(Container $container): Container
    {
        $container->set(static::CLIENT_SESSION, function (Container $container) {
            return new SessionAgentValidationToSessionClientBridge(
                $container->getLocator()->session()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSecurityAuthorizationCheckerService(Container $container): Container
    {
        $container->set(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER);
        });

        return $container;
    }
}
