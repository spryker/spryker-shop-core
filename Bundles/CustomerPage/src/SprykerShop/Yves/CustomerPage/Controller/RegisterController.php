<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 * @method \Spryker\Client\Customer\CustomerClientInterface getClient()
 */
class RegisterController extends AbstractCustomerController
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CUSTOMER_CONFIRMED = 'customer.authorization.account_confirmed';
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MISSING_CONFIRMATION_TOKEN = 'customer.token.invalid';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_CUSTOMER_OVERVIEW
     * @var string
     */
    protected const ROUTE_CUSTOMER_OVERVIEW = 'customer/overview';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_LOGIN
     * @var string
     */
    protected const ROUTE_LOGIN = 'login';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $response = $this->executeIndexAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@CustomerPage/views/register/register.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(Request $request)
    {
        if ($this->isLoggedInCustomer()) {
            return $this->redirectResponseInternal(CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_OVERVIEW);
        }

        $registerForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->getRegisterForm()
            ->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $customerResponseTransfer = $this->registerCustomer($registerForm->getData());

            if ($customerResponseTransfer->getIsSuccess()) {
                $route = static::ROUTE_CUSTOMER_OVERVIEW;
                if ($this->getFactory()->getConfig()->isDoubleOptInEnabled()) {
                    $route = static::ROUTE_LOGIN;
                }

                $this->addSuccessMessage($customerResponseTransfer->getMessage()->getValue());

                return $this->redirectResponseInternal($route);
            }

            $this->processResponseErrors($customerResponseTransfer);
        }

        $loginForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->getLoginForm();

        return [
            'loginForm' => $loginForm->createView(),
            'registerForm' => $registerForm->createView(),
        ];
    }

    /**
     * @param array $customerData
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function registerCustomer(array $customerData)
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($customerData, true);

        $customerResponseTransfer = $this
            ->getFactory()
            ->getAuthenticationHandler()
            ->registerCustomer($customerTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction(Request $request): RedirectResponse
    {
        $response = $this->executeConfirmAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeConfirmAction(Request $request): RedirectResponse
    {
        $token = (string)$request->query->get('token');
        if (!$token) {
            $this->addErrorMessage(static::GLOSSARY_KEY_MISSING_CONFIRMATION_TOKEN);

            return $this->redirectResponseInternal(static::ROUTE_LOGIN);
        }

        $customerTransfer = (new CustomerTransfer())
            ->setRegistrationKey($token);

        $customerResponseTransfer = $this->getFactory()->getCustomerClient()->confirmCustomerRegistration($customerTransfer);

        if ($customerResponseTransfer->getIsSuccess()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_CUSTOMER_CONFIRMED);

            return $this->redirectResponseInternal(static::ROUTE_LOGIN);
        }

        $this->processResponseErrors($customerResponseTransfer);

        return $this->redirectResponseInternal(static::ROUTE_LOGIN);
    }
}
