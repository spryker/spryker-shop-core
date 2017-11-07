<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CustomerPage;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouper;
use SprykerShop\Yves\CustomerPage\Form\FormFactory;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationFailureHandler;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationSuccessHandler;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerSecurityServiceProvider;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerUserProvider;
use SprykerShop\Yves\CustomerPage\Security\Customer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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
     * @return \SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationFailureHandler
     */
    public function createCustomerAuthenticationFailureHandler()
    {
        return new CustomerAuthenticationFailureHandler($this->getFlashMessenger());
    }

    /**
     * @return \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    public function createCustomerUserProvider()
    {
        return new CustomerUserProvider();
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
            CustomerSecurityServiceProvider::FIREWALL_SECURED,
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
     * @return \SprykerShop\Client\NewsletterWidget\NewsletterWidgetClientInterface
     */
    public function getNewsletterPageClient()
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::CLIENT_NEWSLETTER_PAGE);
    }

    /**
     * @return \Spryker\Client\Sales\SalesClientInterface
     */
    public function getSalesClient()
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
     * @return \Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouper
     */
    public function createProductBundleGroupper()
    {
        return new ProductBundleGrouper();
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
    protected function getFlashMessenger()
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
     * @return \Pyz\Client\Customer\CustomerClientInterface TODO: client should come from core or shop
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return string[]
     */
    public function getCustomerOverviewWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::PLUGIN_CUSTOMER_OVERVIEW_WIDGETS);
    }
}
