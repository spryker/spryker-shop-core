<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Twig\TwigFunction;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Shared\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Authenticator\CustomerAuthenticator;
use SprykerShop\Yves\CustomerPage\Authenticator\CustomerAuthenticatorInterface;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToProductBundleClientInterface;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToQuoteClientInteface;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToSalesClientInterface;
use SprykerShop\Yves\CustomerPage\Dependency\Service\CustomerPageToShipmentServiceInterface;
use SprykerShop\Yves\CustomerPage\Expander\CustomerAddressExpander;
use SprykerShop\Yves\CustomerPage\Expander\CustomerAddressExpanderInterface;
use SprykerShop\Yves\CustomerPage\Expander\ShipmentExpander;
use SprykerShop\Yves\CustomerPage\Expander\ShipmentExpanderInterface;
use SprykerShop\Yves\CustomerPage\Expander\ShipmentGroupExpander;
use SprykerShop\Yves\CustomerPage\Expander\ShipmentGroupExpanderInterface;
use SprykerShop\Yves\CustomerPage\Form\FormFactory;
use SprykerShop\Yves\CustomerPage\Mapper\CustomerMapper;
use SprykerShop\Yves\CustomerPage\Mapper\CustomerMapperInterface;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\AccessDeniedHandler;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationFailureHandler;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationSuccessHandler;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerSecurityServiceProvider;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerUserProvider;
use SprykerShop\Yves\CustomerPage\Security\Customer;
use SprykerShop\Yves\CustomerPage\Twig\GetUsernameTwigFunction;
use SprykerShop\Yves\CustomerPage\Twig\IsLoggedTwigFunction;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 */
class CustomerPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CustomerPage\Form\FormFactory
     */
    public function createCustomerFormFactory()
    {
        return new FormFactory();
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationSuccessHandler
     */
    public function createCustomerAuthenticationSuccessHandler()
    {
        return new CustomerAuthenticationSuccessHandler();
    }

    /**
     * @param string|null $targetUrl
     *
     * @return \SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationFailureHandler
     */
    public function createCustomerAuthenticationFailureHandler(?string $targetUrl = null)
    {
        return new CustomerAuthenticationFailureHandler($this->getFlashMessenger(), $targetUrl);
    }

    /**
     * @return \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    public function createCustomerUserProvider()
    {
        return new CustomerUserProvider();
    }

    /**
     * @param string $targetUrl
     *
     * @return \Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface
     */
    public function createAccessDeniedHandler(string $targetUrl): AccessDeniedHandlerInterface
    {
        return new AccessDeniedHandler($targetUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function createSecurityUser(CustomerTransfer $customerTransfer)
    {
        return new Customer(
            $customerTransfer,
            $customerTransfer->getEmail(),
            $customerTransfer->getPassword(),
            [CustomerSecurityServiceProvider::ROLE_USER]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    public function createUsernamePasswordToken(CustomerTransfer $customerTransfer)
    {
        $user = $this->createSecurityUser($customerTransfer);

        return new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            CustomerPageConfig::SECURITY_FIREWALL_NAME,
            [CustomerSecurityServiceProvider::ROLE_USER]
        );
    }

    /**
     * @param string $targetUrl
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createRedirectResponse($targetUrl)
    {
        return new RedirectResponse($targetUrl);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Authenticator\CustomerAuthenticatorInterface
     */
    public function createCustomerAuthenticator(): CustomerAuthenticatorInterface
    {
        return new CustomerAuthenticator(
            $this->getCustomerClient(),
            $this->getTokenStorage()
        );
    }

    /**
     * @return \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    public function getTokenStorage(): TokenStorageInterface
    {
        $application = $this->getApplication();

        return $application['security.token_storage'];
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToSalesClientInterface
     */
    public function getSalesClient(): CustomerPageToSalesClientInterface
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::CLIENT_SALES);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Plugin\AuthenticationHandler
     */
    public function getAuthenticationHandler()
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::PLUGIN_AUTHENTICATION_HANDLER);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToProductBundleClientInterface
     */
    public function getProductBundleClient(): CustomerPageToProductBundleClientInterface
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::CLIENT_PRODUCT_BUNDLE);
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication()
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    public function getFlashMessenger()
    {
        return $this->getApplication()['flash_messenger'];
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Plugin\CheckoutAuthenticationHandlerPluginInterface[]
     */
    public function getCustomerAuthenticationHandlerPlugins()
    {
        return [
            $this->getLoginCheckoutAuthenticationHandlerPlugin(),
            $this->getGuestCheckoutAuthenticationHandlerPlugin(),
            $this->getRegistrationAuthenticationHandlerPlugin(),
        ];
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Plugin\LoginCheckoutAuthenticationHandlerPlugin
     */
    public function getLoginCheckoutAuthenticationHandlerPlugin()
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::PLUGIN_LOGIN_AUTHENTICATION_HANDLER);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Plugin\GuestCheckoutAuthenticationHandlerPlugin
     */
    public function getGuestCheckoutAuthenticationHandlerPlugin()
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::PLUGIN_GUEST_AUTHENTICATION_HANDLER);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Plugin\RegistrationCheckoutAuthenticationHandlerPlugin
     */
    public function getRegistrationAuthenticationHandlerPlugin()
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::PLUGIN_REGISTRATION_AUTHENTICATION_HANDLER);
    }

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    public function getMessenger()
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::FLASH_MESSENGER);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface
     */
    public function getCustomerClient(): CustomerPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToQuoteClientInteface
     */
    public function getQuoteClient(): CustomerPageToQuoteClientInteface
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return string[]
     */
    public function getCustomerOverviewWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::PLUGIN_CUSTOMER_OVERVIEW_WIDGETS);
    }

    /**
     * @return string[]
     */
    public function getCustomerOrderListWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::PLUGIN_CUSTOMER_ORDER_LIST_WIDGETS);
    }

    /**
     * @return string[]
     */
    public function getCustomerOrderViewWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::PLUGIN_CUSTOMER_ORDER_VIEW_WIDGETS);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Dependency\Service\CustomerPageToUtilValidateServiceInterface
     */
    public function getUtilValidateService()
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::SERVICE_UTIL_VALIDATE);
    }

    /**
     * @return string[]
     */
    public function getCustomerMenuItemWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::PLUGIN_CUSTOMER_MENU_ITEM_WIDGETS);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\PreRegistrationCustomerTransferExpanderPluginInterface[]
     */
    public function getPreRegistrationCustomerTransferExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::PLUGIN_PRE_REGISTRATION_CUSTOMER_TRANSFER_EXPANDER);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CustomerRedirectStrategyPluginInterface[]
     */
    public function getAfterLoginCustomerRedirectPlugins(): array
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::PLUGIN_AFTER_LOGIN_CUSTOMER_REDIRECT);
    }

    /**
     * @return \SprykerShop\Yves\AgentPage\Plugin\FixAgentTokenAfterCustomerAuthenticationSuccessPlugin[]
     */
    public function getAfterCustomerAuthenticationSuccessPlugins(): array
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::PLUGIN_AFTER_CUSTOMER_AUTHENTICATION_SUCCESS);
    }

    /**
     * @return \Spryker\Shared\Twig\TwigFunction
     */
    public function createGetUsernameTwigFunction(): TwigFunction
    {
        return new GetUsernameTwigFunction($this->getCustomerClient());
    }

    /**
     * @return \Spryker\Shared\Twig\TwigFunction
     */
    public function createIsLoggedTwigFunction(): TwigFunction
    {
        return new IsLoggedTwigFunction($this->getCustomerClient());
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Dependency\Service\CustomerPageToShipmentServiceInterface
     */
    public function getShipmentService(): CustomerPageToShipmentServiceInterface
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::SERVICE_SHIPMENT);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Mapper\CustomerMapperInterface
     */
    public function createCustomerMapper(): CustomerMapperInterface
    {
        return new CustomerMapper();
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Expander\CustomerAddressExpanderInterface
     */
    public function createCustomerExpander(): CustomerAddressExpanderInterface
    {
        return new CustomerAddressExpander($this->createCustomerMapper());
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Expander\ShipmentGroupExpanderInterface
     */
    public function createShipmentGroupExpander(): ShipmentGroupExpanderInterface
    {
        return new ShipmentGroupExpander();
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Expander\ShipmentExpanderInterface
     */
    public function createShipmentExpander(): ShipmentExpanderInterface
    {
        return new ShipmentExpander();
    }
}
