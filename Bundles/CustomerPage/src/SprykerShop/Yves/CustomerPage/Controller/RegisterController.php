<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CustomerPage\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Customer\Code\Messages;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 * @method \Spryker\Client\Customer\CustomerClientInterface getClient()
 */
class RegisterController extends AbstractCustomerController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        if ($this->isLoggedInCustomer()) {
            return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_CUSTOMER_OVERVIEW);
        }

        $registerForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->createRegisterForm()
            ->handleRequest($request);

        if ($registerForm->isValid()) {
            $customerResponseTransfer = $this->registerCustomer($registerForm->getData());

            if ($customerResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage(Messages::CUSTOMER_AUTHORIZATION_SUCCESS);

                return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_CUSTOMER_OVERVIEW);
            }

            $this->processResponseErrors($customerResponseTransfer);
        }

        $loginForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->createLoginForm();

        return $this->view([
            'loginForm' => $loginForm->createView(),
            'registerForm' => $registerForm->createView(),
        ]);
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
}
