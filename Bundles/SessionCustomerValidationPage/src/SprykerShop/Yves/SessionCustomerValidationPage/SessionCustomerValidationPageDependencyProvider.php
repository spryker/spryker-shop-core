<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionCustomerValidationPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToCustomerClientBridge;
use SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToSessionClientBridge;
use SprykerShop\Yves\SessionCustomerValidationPage\Exception\MissingCustomerSessionSaverPluginException;
use SprykerShop\Yves\SessionCustomerValidationPage\Exception\MissingCustomerSessionValidatorPluginException;
use SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionSaverPluginInterface;
use SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionValidatorPluginInterface;

/**
 * @method \SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig getConfig()
 */
class SessionCustomerValidationPageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGIN_CUSTOMER_SESSION_SAVER = 'PLUGIN_CUSTOMER_SESSION_SAVER';

    /**
     * @var string
     */
    public const PLUGIN_CUSTOMER_SESSION_VALIDATOR = 'PLUGIN_CUSTOMER_SESSION_VALIDATOR';

    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @var string
     */
    public const CLIENT_SESSION = 'CLIENT_SESSION';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addCustomerSessionSaverPlugin($container);
        $container = $this->addCustomerSessionValidatorPlugin($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addSessionClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerSessionSaverPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_CUSTOMER_SESSION_SAVER, function () {
            return $this->getCustomerSessionSaverPlugin();
        });

        return $container;
    }

    /**
     * @throws \SprykerShop\Yves\SessionCustomerValidationPage\Exception\MissingCustomerSessionSaverPluginException
     *
     * @return \SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionSaverPluginInterface
     */
    protected function getCustomerSessionSaverPlugin(): CustomerSessionSaverPluginInterface
    {
        throw new MissingCustomerSessionSaverPluginException(
            sprintf(
                'Missing instance of %s! You need to configure CustomerSessionSaverPlugin ' .
                'in your own %s::%s() ' .
                'to be able to save session for customer.',
                CustomerSessionSaverPluginInterface::class,
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
    protected function addCustomerSessionValidatorPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_CUSTOMER_SESSION_VALIDATOR, function () {
            return $this->getCustomerSessionValidatorPlugin();
        });

        return $container;
    }

    /**
     * @throws \SprykerShop\Yves\SessionCustomerValidationPage\Exception\MissingCustomerSessionValidatorPluginException
     *
     * @return \SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionValidatorPluginInterface
     */
    protected function getCustomerSessionValidatorPlugin(): CustomerSessionValidatorPluginInterface
    {
        throw new MissingCustomerSessionValidatorPluginException(
            sprintf(
                'Missing instance of %s! You need to configure CustomerSessionValidatorPlugin ' .
                'in your own %s::%s() ' .
                'to be able to validate session for customer.',
                CustomerSessionValidatorPluginInterface::class,
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
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new SessionCustomerValidationPageToCustomerClientBridge(
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
    protected function addSessionClient(Container $container): Container
    {
        $container->set(static::CLIENT_SESSION, function (Container $container) {
            return new SessionCustomerValidationPageToSessionClientBridge(
                $container->getLocator()->session()->client(),
            );
        });

        return $container;
    }
}
