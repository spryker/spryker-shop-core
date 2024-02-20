<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Expander;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use SprykerShop\Yves\CustomerPage\Builder\CustomerSecurityOptionsBuilderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Security\Core\Signature\SignatureHasher;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\InteractiveAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\RememberMeAuthenticator;
use Symfony\Component\Security\Http\EventListener\RememberMeListener;
use Symfony\Component\Security\Http\RememberMe\RememberMeHandlerInterface;
use Symfony\Component\Security\Http\RememberMe\ResponseListener;
use Symfony\Component\Security\Http\RememberMe\SignatureRememberMeHandler;

class CustomerRememberMeExpander implements CustomerRememberMeExpanderInterface
{
    /**
     * @var string
     */
    protected const SECURITY_REMEMBER_ME_AUTHENTICATOR = 'security.secured.remember_me.authenticator';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @uses \Spryker\Yves\Http\Plugin\Application\YvesHttpApplicationPlugin::SERVICE_REQUEST_STACK
     *
     * @var string
     */
    protected const REQUEST_STACK = 'request_stack';

    /**
     * @var string
     */
    protected const OPTION_REMEMBER_ME = 'remember_me';

    /**
     * @var string
     */
    protected const KEY_SECRET = 'secret';

    /**
     * @var string
     */
    protected const REMEMBER_ME_COOKIE_NAME = 'REMEMBERME';

    /**
     * @var \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    protected UserProviderInterface $userProvider;

    /**
     * @var \SprykerShop\Yves\CustomerPage\Builder\CustomerSecurityOptionsBuilderInterface
     */
    protected CustomerSecurityOptionsBuilderInterface $customerSecurityOptionsBuilder;

    /**
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     * @param \SprykerShop\Yves\CustomerPage\Builder\CustomerSecurityOptionsBuilderInterface $customerSecurityOptionsBuilder
     */
    public function __construct(
        UserProviderInterface $userProvider,
        CustomerSecurityOptionsBuilderInterface $customerSecurityOptionsBuilder
    ) {
        $this->userProvider = $userProvider;
        $this->customerSecurityOptionsBuilder = $customerSecurityOptionsBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function expand(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
    {
        $this->addRememberMeAuthenticator($container);

        $securityBuilder->addEventSubscriber(function () use ($container): EventSubscriberInterface {
            return new RememberMeListener(
                $this->createSignatureRememberMeHandler($container),
            );
        });

        $securityBuilder->addEventSubscriber(function (): EventSubscriberInterface {
            return new ResponseListener();
        });

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return void
     */
    protected function addRememberMeAuthenticator(ContainerInterface $container): void
    {
        $container->set(static::SECURITY_REMEMBER_ME_AUTHENTICATOR, function () use ($container): InteractiveAuthenticatorInterface {
            return new RememberMeAuthenticator(
                $this->createSignatureRememberMeHandler($container),
                $this->customerSecurityOptionsBuilder->buildOptions()[static::OPTION_REMEMBER_ME][static::KEY_SECRET],
                $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE),
                static::REMEMBER_ME_COOKIE_NAME,
            );
        });
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Security\Http\RememberMe\RememberMeHandlerInterface
     */
    protected function createSignatureRememberMeHandler(ContainerInterface $container): RememberMeHandlerInterface
    {
        return new SignatureRememberMeHandler(
            new SignatureHasher(
                new PropertyAccessor(),
                [],
                $this->customerSecurityOptionsBuilder->buildOptions()[static::OPTION_REMEMBER_ME][static::KEY_SECRET],
            ),
            $this->userProvider,
            $container->get(static::REQUEST_STACK),
            $this->customerSecurityOptionsBuilder->buildOptions(),
        );
    }
}
