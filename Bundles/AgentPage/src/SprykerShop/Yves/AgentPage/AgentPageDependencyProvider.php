<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientBridge;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToAgentClientInterface;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientBridge;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientInterface;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToMessengerClientBridge;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToMessengerClientInterface;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToQuoteClientBridge;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToQuoteClientInterface;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToSessionClientBridge;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToStoreClientBridge;

/**
 * @method \SprykerShop\Yves\AgentPage\AgentPageConfig getConfig()
 */
class AgentPageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_AGENT = 'CLIENT_AGENT';

    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @var string
     */
    public const CLIENT_MESSENGER = 'CLIENT_MESSENGER';

    /**
     * @var string
     */
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    /**
     * @deprecated Use the required service directly.
     *
     * @var string
     */
    public const APPLICATION = 'APPLICATION';

    /**
     * @uses \Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_TOKEN_STORAGE
     *
     * @var string
     */
    public const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @uses \Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_AUTHORIZATION_CHECKER
     *
     * @var string
     */
    public const SERVICE_SECURITY_AUTHORIZATION_CHECKER = 'security.authorization_checker';

    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     *
     * @var string
     */
    public const SERVICE_ROUTER = 'routers';

    /**
     * @uses \Spryker\Yves\Locale\Plugin\Application\LocaleApplicationPlugin::SERVICE_LOCALE
     *
     * @var string
     */
    public const SERVICE_LOCALE = 'locale';

    /**
     * @uses \Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     *
     * @var string
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @var string
     */
    public const PLUGINS_SESSION_POST_IMPERSONATION = 'PLUGINS_SESSION_POST_IMPERSONATION';

    /**
     * @var string
     */
    public const PLUGINS_AGENT_USER_AUTHENTICATION_HANDLER = 'PLUGINS_AGENT_USER_AUTHENTICATION_HANDLER';

    /**
     * @var string
     */
    public const CLIENT_SESSION = 'CLIENT_SESSION';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addAgentClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addMessengerClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addApplication($container);
        $container = $this->addSecurityTokenStorage($container);
        $container = $this->addSecurityAuthorizationChecker($container);
        $container = $this->addRouter($container);
        $container = $this->addLocale($container);
        $container = $this->addRequestStackService($container);
        $container = $this->addSessionPostImpersonationPlugins($container);
        $container = $this->addAgentUserAuthenticationHandlerPlugins($container);
        $container = $this->addSessionClient($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAgentClient(Container $container): Container
    {
        $container->set(static::CLIENT_AGENT, function (Container $container): AgentPageToAgentClientInterface {
            return new AgentPageToAgentClientBridge(
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
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container): AgentPageToCustomerClientInterface {
            return new AgentPageToCustomerClientBridge(
                $container->getLocator()->customer()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMessengerClient(Container $container): Container
    {
        $container->set(static::CLIENT_MESSENGER, function (Container $container): AgentPageToMessengerClientInterface {
            return new AgentPageToMessengerClientBridge(
                $container->getLocator()->messenger()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUOTE, function (Container $container): AgentPageToQuoteClientInterface {
            return new AgentPageToQuoteClientBridge(
                $container->getLocator()->quote()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSecurityTokenStorage(Container $container): Container
    {
        $container->set(static::SERVICE_SECURITY_TOKEN_STORAGE, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_SECURITY_TOKEN_STORAGE);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSecurityAuthorizationChecker(Container $container): Container
    {
        $container->set(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRouter(Container $container): Container
    {
        $container->set(static::SERVICE_ROUTER, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_ROUTER);
        });

        return $container;
    }

    /**
     * @deprecated Use the required service directly.
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplication(Container $container): Container
    {
        $container->set(static::APPLICATION, function () {
            return (new Pimple())->getApplication();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLocale(Container $container): Container
    {
        $container->set(static::SERVICE_LOCALE, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_LOCALE);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRequestStackService(Container $container): Container
    {
        $container->set(static::SERVICE_REQUEST_STACK, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionPostImpersonationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SESSION_POST_IMPERSONATION, function () {
            return $this->getSessionPostImpersonationPlugins();
        });

        return $container;
    }

    /**
     * @return list<\SprykerShop\Yves\AgentPageExtension\Dependency\Plugin\SessionPostImpersonationPluginInterface>
     */
    protected function getSessionPostImpersonationPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAgentUserAuthenticationHandlerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_AGENT_USER_AUTHENTICATION_HANDLER, function () {
            return $this->getAgentUserAuthenticationHandlerPlugins();
        });

        return $container;
    }

    /**
     * @return array<\SprykerShop\Yves\AgentPageExtension\Dependency\Plugin\AuthenticationHandlerPluginInterface>
     */
    protected function getAgentUserAuthenticationHandlerPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionClient(Container $container)
    {
        $container->set(static::CLIENT_SESSION, function () use ($container) {
            return new AgentPageToSessionClientBridge(
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
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new AgentPageToStoreClientBridge(
                $container->getLocator()->store()->client(),
            );
        });

        return $container;
    }
}
