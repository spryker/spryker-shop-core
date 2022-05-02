<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Controller;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Customer\Code\Messages;
use SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\Request;

class AddressController extends AbstractCustomerController
{
    /**
     * @var string
     */
    public const KEY_DEFAULT_BILLING_ADDRESS = 'default_billing_address';

    /**
     * @var string
     */
    public const KEY_DEFAULT_SHIPPING_ADDRESS = 'default_shipping_address';

    /**
     * @var string
     */
    public const KEY_ADDRESSES = 'addresses';

    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction()
    {
        $responseData = $this->executeIndexAction();

        $responseData['customerDeleteFormCloner'] = $this->getFactory()->createFormCloner()
            ->setForm($this->getFactory()->createCustomerFormFactory()->getCustomerAddressDeleteForm());

        return $this->view($responseData, [], '@CustomerPage/views/address/address.twig');
    }

    /**
     * @return array<string, mixed>
     */
    protected function executeIndexAction(): array
    {
        $loggedInCustomerTransfer = $this->getLoggedInCustomerTransfer();

        $customerTransfer = $this
            ->getFactory()
            ->getCustomerClient()
            ->getCustomerByEmail($loggedInCustomerTransfer);

        $addressesTransfer = $this
            ->getFactory()
            ->getCustomerClient()
            ->getAddresses($customerTransfer);

        return $this->getAddressListResponseData($customerTransfer, $addressesTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $response = $this->executeCreateAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@CustomerPage/views/address-create/address-create.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function executeCreateAction(Request $request)
    {
        $customerTransfer = $this->getLoggedInCustomerTransfer();

        $dataProvider = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->createAddressFormDataProvider();
        $addressForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->getAddressForm($dataProvider->getOptions())
            ->handleRequest($request);

        if ($addressForm->isSubmitted() === false) {
            $addressForm->setData($dataProvider->getData());
        }

        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            $this->createAddress($customerTransfer, $addressForm->getData());

            $this->addSuccessMessage(Messages::CUSTOMER_ADDRESS_ADDED);

            return $this->redirectResponseInternal(CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_ADDRESS);
        }

        return [
            'form' => $addressForm->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $response = $this->executeUpdateAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@CustomerPage/views/address-update/address-update.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function executeUpdateAction(Request $request)
    {
        $customerTransfer = $this->getLoggedInCustomerTransfer();

        $dataProvider = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->createAddressFormDataProvider();
        $addressForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->getAddressForm($dataProvider->getOptions())
            ->handleRequest($request);
        $idCustomerAddress = $request->query->getInt('id');

        if (!$addressForm->isSubmitted()) {
            $addressForm->setData($dataProvider->getData($idCustomerAddress));
        } elseif ($addressForm->isValid()) {
            $customerTransfer = $this->processAddressUpdate($customerTransfer, $addressForm->getData());

            $this->addSuccessMessage(Messages::CUSTOMER_ADDRESS_UPDATED);

            return $this->redirectResponseInternal(CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_ADDRESS);
        }

        return [
            'form' => $addressForm->createView(),
            'idCustomerAddress' => $idCustomerAddress,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $customerAddressDeleteForm = $this->getFactory()
            ->createCustomerFormFactory()->getCustomerAddressDeleteForm()->handleRequest($request);

        if (!$customerAddressDeleteForm->isSubmitted() || !$customerAddressDeleteForm->isValid()) {
            $this->addErrorMessage(Messages::CUSTOMER_ADDRESS_DELETE_FAILED);

            return $this->redirectResponseInternal(CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_ADDRESS);
        }

        $customerTransfer = $this->getLoggedInCustomerTransfer();

        $addressTransfer = new AddressTransfer();
        $addressTransfer
            ->setIdCustomerAddress($request->query->getInt('id'))
            ->setFkCustomer($customerTransfer->getIdCustomer());

        /** @var \Generated\Shared\Transfer\AddressTransfer|null $addressTransfer */
        $addressTransfer = $this
            ->getFactory()
            ->getCustomerClient()
            ->deleteAddress($addressTransfer);

        if ($addressTransfer !== null) {
            $this->getFactory()
                ->getCustomerClient()
                ->markCustomerAsDirty();

            $this->addSuccessMessage(Messages::CUSTOMER_ADDRESS_DELETE_SUCCESS);

            return $this->redirectResponseInternal(CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_REFRESH_ADDRESS);
        }

        $this->addErrorMessage(Messages::CUSTOMER_ADDRESS_DELETE_FAILED);

        return $this->redirectResponseInternal(CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_ADDRESS);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function refreshAction()
    {
        return $this->redirectResponseInternal(CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_ADDRESS);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\AddressesTransfer|null $addressesTransfer
     *
     * @return array
     */
    protected function getAddressListResponseData(CustomerTransfer $customerTransfer, ?AddressesTransfer $addressesTransfer = null)
    {
        $responseData = [
            static::KEY_DEFAULT_BILLING_ADDRESS => null,
            static::KEY_DEFAULT_SHIPPING_ADDRESS => null,
            static::KEY_ADDRESSES => null,
        ];

        if ($addressesTransfer === null) {
            return $responseData;
        }

        foreach ($addressesTransfer->getAddresses() as $addressTransfer) {
            if ((int)$addressTransfer->getIdCustomerAddress() === (int)$customerTransfer->getDefaultBillingAddress()) {
                $addressTransfer->setIsDefaultBilling(true);
            }

            if ((int)$addressTransfer->getIdCustomerAddress() === (int)$customerTransfer->getDefaultShippingAddress()) {
                $addressTransfer->setIsDefaultShipping(true);
            }

            $responseData[static::KEY_ADDRESSES][] = $addressTransfer;
        }

        return $responseData;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param array $addressData
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createAddress(CustomerTransfer $customerTransfer, array $addressData)
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer->fromArray($addressData);
        $addressTransfer
            ->setFkCustomer($customerTransfer->getIdCustomer());

        $customerTransfer = $this
            ->getFactory()
            ->getCustomerClient()
            ->createAddressAndUpdateCustomerDefaultAddresses($addressTransfer);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param array $addressData
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function processAddressUpdate(CustomerTransfer $customerTransfer, array $addressData)
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer->fromArray($addressData);

        $addressTransfer->setFkCustomer($customerTransfer->getIdCustomer());

        $customerTransfer = $this
            ->getFactory()
            ->getCustomerClient()
            ->updateAddressAndCustomerDefaultAddresses($addressTransfer);

        return $customerTransfer;
    }
}
